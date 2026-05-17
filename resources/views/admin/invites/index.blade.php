@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-envelope"></i> Convites Enviados</h4>
        <a href="{{ route('admin.invites.create') }}" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Novo Convite
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Formulário de Filtro -->
        <form method="GET" action="{{ route('admin.invites.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Pesquisar</label>
                    <input type="text" name="search" class="form-control" placeholder="Email, nome ou empresa..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendentes</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Aceitos</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expirados</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.invites.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Limpar
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Nome</th>
                        <th>Empresa</th>
                        <th>Setor</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th>Expira em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invites as $invite)
                    <tr>
                        <td>{{ $invite->email }}</td>
                        <td>{{ $invite->name }}</td>
                        <td>{{ $invite->company->name ?? '-' }}</td>
                        <td>{{ $invite->sector->name ?? '-' }}</td>
                        <td>
                            @if($invite->role == 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @elseif($invite->role == 'technician')
                                <span class="badge bg-info">Técnico</span>
                            @else
                                <span class="badge bg-secondary">Usuário</span>
                            @endif
                        </td>
                        <td>
                            @if($invite->accepted_at)
                                <span class="badge bg-success">Aceito</span>
                            @elseif($invite->isExpired())
                                <span class="badge bg-danger">Expirado</span>
                            @else
                                <span class="badge bg-warning">Pendente</span>
                            @endif
                        </td>
                        <td>
                            @if(!$invite->accepted_at && !$invite->isExpired())
                                {{ $invite->expires_at->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if(!$invite->accepted_at)
                            <form action="{{ route('admin.invites.destroy', $invite) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remover este convite?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @else
                                <span class="text-muted">Finalizado</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">Nenhum convite encontrado</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $invites->links() }}
    </div>
</div>
@endsection