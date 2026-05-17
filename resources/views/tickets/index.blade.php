@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-ticket"></i> Meus Chamados</h4>
        <a href="{{ route('tickets.create') }}" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Novo Chamado
        </a>
    </div>
    <div class="card-body">
        
        <!-- Busca por Nº Chamado ou Título -->
        <div class="row mb-3">
            <div class="col-md-4">
                <form method="GET" action="{{ route('tickets.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Buscar por Nº Chamado ou Título..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
        
        <!-- Filtros -->
        <form method="GET" action="{{ route('tickets.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Filtrar por Status</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="aberto" {{ request('status') == 'aberto' ? 'selected' : '' }}>Aberto</option>
                        <option value="em_andamento" {{ request('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                        <option value="resolvido" {{ request('status') == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                        <option value="fechado" {{ request('status') == 'fechado' ? 'selected' : '' }}>Fechado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filtrar por Prioridade</label>
                    <select name="priority" class="form-select">
                        <option value="">Todas</option>
                        <option value="baixa" {{ request('priority') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                        <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="urgente" {{ request('priority') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filtrar por Setor</label>
                    <select name="sector_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($sectors as $sector)
                            <option value="{{ $sector->id }}" {{ request('sector_id') == $sector->id ? 'selected' : '' }}>
                                {{ $sector->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                    <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Limpar
                    </a>
                </div>
            </div>
        </form>
        
        @if($tickets->isEmpty())
            <div class="text-center py-4">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <p class="mt-2">Nenhum chamado encontrado.</p>
                <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                    Abrir primeiro chamado
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nº Chamado</th>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Setor</th>
                            <th>Prioridade</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr>
                            <td><strong>{{ $ticket->ticket_number }}</strong></td>
                            <td>#{{ $ticket->id }}</td>
                            <td>{{ $ticket->title }}</td>
                            <td>{{ $ticket->sector->name ?? 'N/A' }}</td>
                            <td>
                                @if($ticket->priority == 'urgente')
                                    <span class="badge bg-danger">Urgente</span>
                                @elseif($ticket->priority == 'normal')
                                    <span class="badge bg-primary">Normal</span>
                                @else
                                    <span class="badge bg-secondary">Baixa</span>
                                @endif
                            </td>
                            <td>
                                @if($ticket->status == 'aberto')
                                    <span class="badge bg-warning">Aberto</span>
                                @elseif($ticket->status == 'em_andamento')
                                    <span class="badge bg-info">Em Andamento</span>
                                @elseif($ticket->status == 'resolvido')
                                    <span class="badge bg-success">Resolvido</span>
                                @else
                                    <span class="badge bg-secondary">Fechado</span>
                                @endif
                            </td>
                            <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $tickets->links() }}
        @endif
    </div>
</div>
@endsection