@extends('layouts.app')

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<style>
    .bg-primary .attachment-link,
    .bg-primary a {
        color: #ffffff !important;
        background-color: rgba(255, 255, 255, 0.15);
        padding: 4px 8px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-top: 5px;
        font-size: 12px;
    }
    
    .bg-primary .attachment-link:hover,
    .bg-primary a:hover {
        background-color: #ffffff !important;
        color: #0d6efd !important;
    }
    
    .bg-light .attachment-link,
    .bg-light a {
        color: #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.1);
        padding: 4px 8px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-top: 5px;
        font-size: 12px;
    }
    
    .bg-light .attachment-link:hover,
    .bg-light a:hover {
        background-color: #0d6efd !important;
        color: #ffffff !important;
    }
</style>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-chat-dots"></i> 
                    <span class="badge bg-dark me-2">Nº: {{ $ticket->ticket_number }}</span>
                    Chamado #{{ $ticket->id }} - {{ $ticket->title }}
                </h5>
                <span class="badge bg-light text-dark">
                    {{ ucfirst($ticket->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong><i class="bi bi-person"></i> Solicitante:</strong>
                        <p>{{ $ticket->user->name }} ({{ $ticket->user->sector->name ?? 'N/A' }})</p>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="bi bi-calendar"></i> Abertura:</strong>
                        <p>{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong><i class="bi bi-geo-alt"></i> Local do Problema:</strong>
                        <p>{{ ucfirst($ticket->location) }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="bi bi-flag"></i> Prioridade:</strong>
                        <p>
                            @if($ticket->priority == 'urgente')
                                <span class="badge bg-danger">Urgente</span>
                            @elseif($ticket->priority == 'normal')
                                <span class="badge bg-primary">Normal</span>
                            @else
                                <span class="badge bg-secondary">Baixa</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mb-3">
                    <strong><i class="bi bi-file-text"></i> Descrição:</strong>
                    <p class="border rounded p-3 bg-light">{{ $ticket->description }}</p>
                </div>

                @if($ticket->attachments->where('reply_id', null)->count() > 0)
                <div class="mb-3">
                    <strong><i class="bi bi-paperclip"></i> Anexos do Chamado:</strong>
                    <div class="mt-2">
                        @foreach($ticket->attachments->where('reply_id', null) as $attachment)
                            <a href="{{ url('storage/attachments/' . $attachment->path) }}" target="_blank" class="btn btn-sm btn-outline-secondary me-2 mb-2">
                                <i class="bi bi-file-earmark"></i> {{ $attachment->original_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-chat"></i> Conversa</h5>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                @if($ticket->replies->count() > 0)
                    @foreach($ticket->replies as $reply)
                        <div class="mb-3 {{ $reply->user_id == auth()->id() ? 'text-end' : '' }}">
                            <div class="d-inline-block p-3 rounded {{ $reply->user_id == auth()->id() ? 'bg-primary text-white' : 'bg-light' }}" 
                                 style="max-width: 80%; text-align: left;">
                                <small class="d-block mb-1">
                                    <strong>{{ $reply->user->name }}</strong> 
                                    {{ $reply->is_technician ? '(Técnico)' : '' }}
                                    <span class="small">- {{ $reply->created_at->format('d/m/Y H:i') }}</span>
                                </small>
                                <p class="mb-0">{{ $reply->message }}</p>
                                
                                @if($reply->attachments && $reply->attachments->count() > 0)
                                    <div class="mt-2">
                                        @foreach($reply->attachments as $attachment)
                                            <a href="{{ url('storage/attachments/' . $attachment->path) }}" target="_blank" class="attachment-link">
                                                <i class="bi bi-paperclip"></i> {{ $attachment->original_name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Nenhuma mensagem ainda. Seja o primeiro a responder!</p>
                @endif
            </div>
            
            @if($ticket->status == 'resolvido' || $ticket->status == 'fechado')
                <div class="card-footer bg-light">
                    <p class="text-muted mb-0 text-center">
                        <i class="bi bi-lock-fill"></i> 
                        Este chamado está <strong>{{ ucfirst($ticket->status) }}</strong>. Não é possível enviar novas mensagens.
                    </p>
                    @if(auth()->user()->isAdmin() || auth()->user()->isTechnician())
                        <p class="text-muted small text-center mt-2 mb-0">
                            <i class="bi bi-arrow-repeat"></i> 
                            Para reabrir o chamado, altere o status para "Aberto" ou "Em Andamento" nas ações ao lado.
                        </p>
                    @endif
                </div>
            @else
                <div class="card-footer">
                    <form action="{{ route('tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            <textarea name="message" class="form-control" rows="2" placeholder="Digite sua mensagem..." required></textarea>
                        </div>
                        <div class="mb-2">
                            <input type="file" name="attachments[]" multiple class="form-control form-control-sm" accept="image/*,.jpg,.jpeg,.png,.gif,.bmp,.webp,.pdf,.doc,.docx,.xls,.xlsx,.zip">
                            <small class="text-muted">Anexar arquivos (JPG, PNG, GIF, BMP, WEBP, PDF, DOC, XLS, ZIP) - Max 5MB</small>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Enviar
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-gear"></i> Ações</h5>
            </div>
            <div class="card-body">
                @if(auth()->user()->isAdmin() || auth()->user()->isTechnician())
                    <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="status" class="form-label">Alterar Status:</label>
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="aberto" {{ $ticket->status == 'aberto' ? 'selected' : '' }}>Aberto</option>
                                <option value="em_andamento" {{ $ticket->status == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                                <option value="resolvido" {{ $ticket->status == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                                <option value="fechado" {{ $ticket->status == 'fechado' ? 'selected' : '' }}>Fechado</option>
                            </select>
                        </div>
                    </form>
                    <hr>
                @endif

                @if($ticket->status == 'fechado' || $ticket->status == 'resolvido')
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> Chamado finalizado
                    </div>
                @endif

                @if(auth()->user()->id == $ticket->user_id && $ticket->status != 'fechado' && $ticket->status != 'resolvido')
                    <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="fechado">
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Deseja fechar este chamado?')">
                            <i class="bi bi-x-circle"></i> Fechar Chamado
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection