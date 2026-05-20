
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-ticket-detailed"></i> Chamados
            </h4>
            <a href="{{ route('tickets.create') }}" class="btn btn-light">
                <i class="bi bi-plus-circle"></i> Novo Chamado
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
            
            <!-- Filtros -->
            <form method="GET" action="{{ route('tickets.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Buscar</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Título ou número..." value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="aberto" {{ request('status') == 'aberto' ? 'selected' : '' }}>Aberto</option>
                            <option value="em_andamento" {{ request('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="resolvido" {{ request('status') == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                            <option value="fechado" {{ request('status') == 'fechado' ? 'selected' : '' }}>Fechado</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Prioridade</label>
                        <select name="priority" class="form-select">
                            <option value="">Todas</option>
                            <option value="baixa" {{ request('priority') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                            <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="alta" {{ request('priority') == 'alta' ? 'selected' : '' }}>Alta</option>
                            <option value="urgente" {{ request('priority') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Setor</label>
                        <select name="sector_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($sectors as $sector)
                                <option value="{{ $sector->id }}" {{ request('sector_id') == $sector->id ? 'selected' : '' }}>
                                    {{ $sector->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 me-2">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <a href="{{ route('tickets.index') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-x-circle"></i> Limpar
                        </a>
                    </div>
                </div>
            </form>
            
            <!-- Tabela de Chamados -->
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Nº Chamado</th>
                            <th>Título</th>
                            <th>Setor</th>
                            <th>Prioridade</th>
                            <th>Status</th>
                            <th>Solicitante</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                        <tr>
                            <td>
                                <strong>#{{ $ticket->ticket_number }}</strong>
                            </td>
                            <td>{{ Str::limit($ticket->title, 40) }}</td>
                            <td>{{ $ticket->sector->name ?? '-' }}</td>
                            <td>
                                @if($ticket->priority == 'urgente')
                                    <span class="badge bg-danger">Urgente</span>
                                @elseif($ticket->priority == 'alta')
                                    <span class="badge bg-warning text-dark">Alta</span>
                                @elseif($ticket->priority == 'normal')
                                    <span class="badge bg-info text-dark">Normal</span>
                                @else
                                    <span class="badge bg-secondary">Baixa</span>
                                @endif
                            </td>
                            <td>
                                @if($ticket->status == 'aberto')
                                    <span class="badge bg-warning text-dark">Aberto</span>
                                @elseif($ticket->status == 'em_andamento')
                                    <span class="badge bg-primary">Em Andamento</span>
                                @elseif($ticket->status == 'resolvido')
                                    <span class="badge bg-success">Resolvido</span>
                                @else
                                    <span class="badge bg-secondary">Fechado</span>
                                @endif
                            </td>
                            <td>{{ $ticket->user->name ?? '-' }}</td>
                            <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1"></i><br>
                                Nenhum chamado encontrado.
                                <br>
                                <a href="{{ route('tickets.create') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Abrir primeiro chamado
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="d-flex justify-content-center mt-3">
                {{ $tickets->links() }}
            </div>
            
        </div>
    </div>
</div>
@endsection