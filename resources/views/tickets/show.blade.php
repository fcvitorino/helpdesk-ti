@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-ticket-detailed"></i> Chamado #{{ $ticket->ticket_number }}
                    </h4>
                    <a href="{{ route('tickets.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
                <div class="card-body">
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <div class="row">
                        <!-- COLUNA ESQUERDA - CONTEÚDO PRINCIPAL -->
                        <div class="col-md-8">
                            
                            <!-- Título -->
                            <div class="mb-4">
                                <h5 class="fw-bold">{{ $ticket->title }}</h5>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i> Abertura: {{ $ticket->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            
                            <!-- Descrição -->
                            <div class="mb-4">
                                <strong><i class="bi bi-chat-left-text"></i> Descrição:</strong>
                                <div class="border rounded p-3 bg-light mt-2">
                                    {{ $ticket->description }}
                                </div>
                            </div>
                            
                            <!-- SEÇÃO DE COMENTÁRIOS ESTILIZADA -->
                            <div class="mt-5">
                                <div class="card bg-light border-0 shadow-sm">
                                    <div class="card-header bg-secondary text-white">
                                        <i class="bi bi-chat-dots-fill"></i> Chat do Chamado
                                        <span class="badge bg-light text-dark float-end">{{ $replies->count() }} mensagens</span>
                                    </div>
                                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                        
                                        @forelse($replies as $reply)
                                            @php
                                                $isMe = $reply->user_id == auth()->id();
                                                $isSupport = $reply->is_support;
                                            @endphp
                                            
                                            <div class="mb-4 {{ $isMe || $isSupport ? 'text-end' : 'text-start' }}">
                                                <div class="d-inline-block" style="max-width: 75%;">
                                                    <small class="text-muted d-block mb-1 {{ $isMe || $isSupport ? 'text-end' : 'text-start' }}">
                                                        <strong>{{ $reply->user->name }}</strong> • {{ $reply->created_at->format('d/m/Y H:i') }}
                                                    </small>
                                                    <div class="rounded-3 p-3 shadow-sm {{ $isMe || $isSupport ? 'bg-primary bg-opacity-10 text-dark' : 'bg-white border' }}">
                                                        <p class="mb-2">{{ $reply->message }}</p>
                                                        
                                                        @if($reply->attachments && $reply->attachments->count() > 0)
                                                            <div class="mt-2">
                                                                @foreach($reply->attachments as $attachment)
                                                                    <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank" 
                                                                       class="btn btn-sm btn-outline-secondary">
                                                                        <i class="bi bi-paperclip"></i> {{ $attachment->original_name }}
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center text-muted py-5">
                                                <i class="bi bi-chat-square-text fs-1"></i>
                                                <p class="mt-3 mb-0">Nenhuma mensagem ainda.</p>
                                                <small>Seja o primeiro a comentar!</small>
                                            </div>
                                        @endforelse
                                        
                                    </div>
                                    
                                    <!-- 🔒 FORMULÁRIO CONDICIONAL (bloqueado para resolvido/fechado) -->
                                    <div class="card-footer bg-white">
                                        @if($ticket->status == 'resolvido' || $ticket->status == 'fechado')
                                            <div class="alert alert-warning text-center mb-0">
                                                <i class="bi bi-lock-fill"></i> 
                                                <strong>
                                                    @if($ticket->status == 'resolvido')
                                                        ✅ Este chamado está RESOLVIDO.
                                                    @else
                                                        🔒 Este chamado está FECHADO.
                                                    @endif
                                                </strong>
                                                Não é possível adicionar novos comentários.
                                            </div>
                                        @else
                                            <form method="POST" action="{{ route('tickets.comments', $ticket) }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <textarea name="message" rows="2" class="form-control" 
                                                                  placeholder="Digite sua mensagem..." required></textarea>
                                                    </div>
                                                    <div class="col-8">
                                                        <input type="file" name="attachment" class="form-control form-control-sm">
                                                        <small class="text-muted">Anexar arquivo (imagem, PDF, até 5MB)</small>
                                                    </div>
                                                    <div class="col-4">
                                                        <button type="submit" class="btn btn-primary w-100">
                                                            <i class="bi bi-send"></i> Enviar
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <!-- COLUNA DIREITA - INFORMAÇÕES DO CHAMADO -->
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="fw-bold"><i class="bi bi-info-circle"></i> Informações</h6>
                                    <hr>
                                    
                                    <div class="mb-3">
                                        <strong>Status:</strong><br>
                                        @if($ticket->status == 'aberto')
                                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Aberto</span>
                                        @elseif($ticket->status == 'em_andamento')
                                            <span class="badge bg-primary"><i class="bi bi-gear"></i> Em Andamento</span>
                                        @elseif($ticket->status == 'resolvido')
                                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Resolvido</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="bi bi-archive"></i> Fechado</span>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Prioridade:</strong><br>
                                        @if($ticket->priority == 'urgente')
                                            <span class="badge bg-danger">🔴 Urgente</span>
                                        @elseif($ticket->priority == 'alta')
                                            <span class="badge bg-warning text-dark">🟠 Alta</span>
                                        @elseif($ticket->priority == 'normal')
                                            <span class="badge bg-info text-dark">🟢 Normal</span>
                                        @else
                                            <span class="badge bg-secondary">🔵 Baixa</span>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Local:</strong><br>
                                        @if($ticket->location == 'navegador') 🌐 Navegador
                                        @elseif($ticket->location == 'rede') 🔌 Rede
                                        @elseif($ticket->location == 'hardware') 💻 Hardware
                                        @elseif($ticket->location == 'impressora') 🖨️ Impressora
                                        @elseif($ticket->location == 'perifericos') 🖱️ Periféricos
                                        @elseif($ticket->location == 'monitor') 🖥️ Monitor
                                        @else 📦 Outros
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Solicitante:</strong><br>
                                        <i class="bi bi-person-circle"></i> {{ $ticket->user->name ?? '-' }}
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Setor:</strong><br>
                                        <i class="bi bi-building"></i> {{ $ticket->sector->name ?? '-' }}
                                    </div>
                                    
                                    @if(auth()->user()->isAdmin() || auth()->user()->isTechnician())
                                        <hr>
                                        <form method="POST" action="{{ route('tickets.status', $ticket) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="mb-3">
                                                <label class="form-label">Alterar Status</label>
                                                <select name="status" class="form-select form-select-sm">
                                                    <option value="aberto" {{ $ticket->status == 'aberto' ? 'selected' : '' }}>🟡 Aberto</option>
                                                    <option value="em_andamento" {{ $ticket->status == 'em_andamento' ? 'selected' : '' }}>🔵 Em Andamento</option>
                                                    <option value="resolvido" {{ $ticket->status == 'resolvido' ? 'selected' : '' }}>✅ Resolvido</option>
                                                    <option value="fechado" {{ $ticket->status == 'fechado' ? 'selected' : '' }}>⚪ Fechado</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                <i class="bi bi-pencil"></i> Atualizar Status
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection