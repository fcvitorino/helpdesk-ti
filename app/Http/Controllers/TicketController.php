<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Sector;
use App\Models\Reply;
use App\Models\Attachment;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (($user->isAdmin() || $user->isTechnician()) && !session()->has('selected_company_id')) {
            return redirect()->route('company.select');
        }
        
        $companyId = session('selected_company_id', $user->company_id);
        
        $query = Ticket::with(['user', 'sector']);
        
        if ($user->isAdmin() || $user->isTechnician()) {
            $query->where('company_id', $companyId);
        } else {
            $query->where('user_id', $user->id);
        }
        
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
        
        $sectors = Sector::where('company_id', $companyId)->orderBy('name')->get();
        
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

        NotificationHelper::notifyNewTicket($ticket);

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

        $oldStatus = $ticket->status;
        $ticket->status = $request->status;
        if ($request->status == 'resolvido') {
            $ticket->resolved_at = now();
        }
        $ticket->save();

        NotificationHelper::notifyStatusChange($ticket, $oldStatus, $request->status, auth()->user());

        return back()->with('success', 'Status atualizado com sucesso!');
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        $this->authorizeAccess($ticket);
        
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

        NotificationHelper::notifyNewComment($ticket, $reply, auth()->user());
        
        return back()->with('success', 'Comentário adicionado com sucesso!');
    }

    private function authorizeAccess(Ticket $ticket)
    {
        $user = auth()->user();

        if ($user->isAdmin()) return true;

        if ($user->isTechnician()) {
            if ($ticket->company_id == session('selected_company_id', $user->company_id)) {
                return true;
            }
            abort(403, 'Acesso negado.');
        }

        if ($ticket->user_id != $user->id) {
            abort(403, 'Acesso negado.');
        }

        return true;
    }
}