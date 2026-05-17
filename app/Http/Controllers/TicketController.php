<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Attachment;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isAdmin() && session()->has('selected_company_id')) {
            $companyId = session('selected_company_id');
        } else {
            $companyId = $user->company_id ?? 1;
        }
        
        $query = Ticket::where('company_id', $companyId)->with(['user', 'sector']);
        
        // Se for usuário comum, vê apenas seus chamados
        if (!$user->isAdmin() && !$user->isTechnician()) {
            $query->where('user_id', $user->id);
        }
        
        // Filtro por número do chamado ou título (busca)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }
        
        // Aplicar filtro por status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Aplicar filtro por prioridade
        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }
        
        // Aplicar filtro por setor
        if ($request->has('sector_id') && $request->sector_id != '') {
            $query->where('sector_id', $request->sector_id);
        }
        
        $tickets = $query->latest()->paginate(15);
        $tickets->appends($request->all());
        
        $sectors = Sector::where('company_id', $companyId)->orderBy('name')->get();
        
        return view('tickets.index', compact('tickets', 'sectors'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string',
            'priority' => 'required|in:baixa,normal,urgente',
            'description' => 'required|string',
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,gif,bmp,webp,pdf,doc,docx,xls,xlsx,zip',
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'sector_id' => Auth::user()->sector_id,
            'company_id' => Auth::user()->company_id ?? 1,
            'title' => $validated['title'],
            'location' => $validated['location'],
            'priority' => $validated['priority'],
            'description' => $validated['description'],
            'status' => 'aberto',
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $savedPath = str_replace('attachments/', '', $path);
                
                Attachment::create([
                    'ticket_id' => $ticket->id,
                    'filename' => $file->hashName(),
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $savedPath,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('tickets.index')
            ->with('success', 'Chamado #' . $ticket->id . ' aberto com sucesso! Número: ' . $ticket->ticket_number);
    }

    public function show(Ticket $ticket)
    {
        $this->authorizeAccess($ticket);
        return view('tickets.show', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorizeAccess($ticket);
        
        $validated = $request->validate([
            'status' => 'required|in:aberto,em_andamento,resolvido,fechado',
        ]);

        if ($validated['status'] == 'resolvido' || $validated['status'] == 'fechado') {
            $ticket->resolved_at = now();
        }

        $ticket->update($validated);

        return back()->with('success', 'Status atualizado com sucesso!');
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,gif,bmp,webp,pdf,doc,docx,xls,xlsx,zip',
        ]);

        $reply = $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_technician' => auth()->user()->isTechnician() || auth()->user()->isAdmin(),
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $savedPath = str_replace('attachments/', '', $path);
                
                Attachment::create([
                    'ticket_id' => $ticket->id,
                    'reply_id' => $reply->id,
                    'filename' => $file->hashName(),
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $savedPath,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        if ($ticket->status == 'aberto' && auth()->user()->isTechnician()) {
            $ticket->update(['status' => 'em_andamento']);
        }

        return back()->with('success', 'Mensagem enviada com sucesso!');
    }

    private function authorizeAccess(Ticket $ticket)
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return;
        }
        
        if ($user->isTechnician()) {
            return;
        }
        
        if ($ticket->user_id !== $user->id) {
            abort(403, 'Você não tem permissão para acessar este chamado.');
        }
    }
}