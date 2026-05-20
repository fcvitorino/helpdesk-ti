<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Sector;
use App\Models\Reply;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Listar chamados com base no perfil do usuário
     */
    public function index()
    {
        $user = Auth::user();
        $companyId = session('selected_company_id', $user->company_id);
        
        // 🔒 REGRA DE VISIBILIDADE:
        // - Admin: vê todos os chamados da empresa selecionada
        // - Técnico: vê todos os chamados da empresa selecionada
        // - Usuário comum: vê APENAS os chamados que ele mesmo abriu
        
        $query = Ticket::with(['user', 'sector']);
        
        if ($user->isAdmin() || $user->isTechnician()) {
            // Admin ou Técnico: vê todos os chamados da empresa selecionada
            $query->where('company_id', $companyId);
        } else {
            // Usuário comum: vê APENAS seus próprios chamados
            $query->where('user_id', $user->id);
        }
        
        // Aplicar filtros (se houver)
        if (request('search')) {
            $query->where(function($q) {
                $q->where('ticket_number', 'like', '%' . request('search') . '%')
                  ->orWhere('title', 'like', '%' . request('search') . '%');
            });
        }
        
        if (request('status')) {
            $query->where('status', request('status'));
        }
        
        if (request('priority')) {
            $query->where('priority', request('priority'));
        }
        
        if (request('sector_id')) {
            $query->where('sector_id', request('sector_id'));
        }
        
        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Buscar setores para o filtro (apenas para admin/técnico)
        $sectors = collect();
        if ($user->isAdmin() || $user->isTechnician()) {
            $sectors = Sector::where('company_id', $companyId)->orderBy('name')->get();
        } else {
            // Usuário comum: só vê setores relacionados aos seus chamados
            $sectorIds = Ticket::where('user_id', $user->id)->distinct()->pluck('sector_id');
            $sectors = Sector::whereIn('id', $sectorIds)->orderBy('name')->get();
        }
        
        return view('tickets.index', compact('tickets', 'sectors'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    private function generateTicketNumber()
    {
        $date = date('Ymd');
        $lastTicket = Ticket::where('ticket_number', 'like', $date . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTicket) {
            $lastNumber = intval(substr($lastTicket->ticket_number, -4));
            $newNumber = $lastNumber + 1;
            $sequential = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        } else {
            $sequential = '0001';
        }

        return $date . $sequential;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'priority' => 'required|in:baixa,normal,alta,urgente',
        ]);

        $ticket = Ticket::create([
            'ticket_number' => $this->generateTicketNumber(),
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'priority' => $request->priority,
            'status' => 'aberto',
            'company_id' => session('selected_company_id', auth()->user()->company_id),
            'user_id' => auth()->id(),
            'sector_id' => auth()->user()->sector_id,
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', "Chamado {$ticket->ticket_number} aberto com sucesso!");
    }

    public function show(Ticket $ticket)
    {
        $this->authorizeAccess($ticket);
        $replies = $ticket->replies()->with('user')->orderBy('created_at', 'asc')->get();
        return view('tickets.show', compact('ticket', 'replies'));
    }

    public function edit(Ticket $ticket)
    {
        $this->authorizeAccess($ticket);
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorizeAccess($ticket);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'priority' => 'required|in:baixa,normal,alta,urgente',
        ]);

        $ticket->update([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'priority' => $request->priority,
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Chamado atualizado com sucesso!');
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorizeAccess($ticket);
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Chamado excluído com sucesso!');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $this->authorizeAccess($ticket);

        $request->validate([
            'status' => 'required|in:aberto,em_andamento,resolvido,fechado',
        ]);

        $ticket->status = $request->status;
        if ($request->status == 'resolvido') {
            $ticket->resolved_at = now();
        }
        $ticket->save();

        return back()->with('success', 'Status atualizado com sucesso!');
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        $this->authorizeAccess($ticket);
        
        // Bloqueia comentários em tickets resolvidos ou fechados
        if ($ticket->status == 'resolvido' || $ticket->status == 'fechado') {
            return back()->with('error', '❌ Este chamado está ' . $ticket->status . '. Não é possível adicionar novos comentários.');
        }
        
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:5120',
        ]);
        
        $reply = Reply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_support' => in_array(auth()->user()->role, ['admin', 'technician']),
        ]);
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('attachments/replies', $filename, 'public');
            
            Attachment::create([
                'ticket_id' => $ticket->id,
                'reply_id' => $reply->id,
                'user_id' => auth()->id(),
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);
        }
        
        if ($ticket->status == 'aberto') {
            $ticket->status = 'em_andamento';
            $ticket->save();
        }
        
        return back()->with('success', 'Comentário adicionado com sucesso!');
    }

    /**
     * Verificar permissão de acesso ao chamado
     * - Admin: acesso total (qualquer chamado)
     * - Técnico: acesso a chamados da sua empresa
     * - Usuário comum: acesso APENAS aos seus próprios chamados
     */
    private function authorizeAccess(Ticket $ticket)
    {
        $user = auth()->user();

        // Admin tem acesso total
        if ($user->isAdmin()) {
            return true;
        }
        
        // Técnico vê chamados da sua empresa
        if ($user->isTechnician()) {
            if ($ticket->company_id == session('selected_company_id', $user->company_id)) {
                return true;
            }
            abort(403, 'Acesso negado. Você não tem permissão para visualizar este chamado.');
        }
        
        // Usuário comum: só vê seus próprios chamados
        if ($ticket->user_id != $user->id) {
            abort(403, 'Acesso negado. Você só pode visualizar seus próprios chamados.');
        }
        
        return true;
    }
}