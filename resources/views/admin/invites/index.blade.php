@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Convites</h4>
        <a href="{{ route('admin.invites.create') }}" class="btn btn-light">
            <i class="bi bi-envelope-plus"></i> Novo Convite
        </a>
    </div>
    <div class="card-body">
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>E-mail</th>
                        <th>Perfil</th>
                        <th>Empresa</th>
                        <th>Setor</th>
                        <th>Status</th>
                        <th>Expira em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invites as $invite)
                    <tr>
                        <td>{{ $invite->id }}</td>
                        <td>{{ $invite->email }}</td>
                        <td>
                            @if($invite->role == 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @elseif($invite->role == 'technician')
                                <span class="badge bg-info">Técnico</span>
                            @else
                                <span class="badge bg-secondary">Usuário</span>
                            @endif
                        </td>
                        <td>{{ $invite->company->name ?? '-' }}</td>
                        <td>{{ $invite->sector->name ?? '-' }}</td>
                        <td>
                            @if($invite->status == 'pending')
                                <span class="badge bg-warning text-dark">Pendente</span>
                            @elseif($invite->status == 'accepted')
                                <span class="badge bg-success">Aceito</span>
                            @else
                                <span class="badge bg-danger">Expirado</span>
                            @endif
                        </td>
                        <td>{{ $invite->expires_at ? $invite->expires_at->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($invite->status == 'pending')
                                <form action="{{ route('admin.invites.resend', $invite) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Reenviar</button>
                                </form>
                                <form action="{{ route('admin.invites.cancel', $invite) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
                                </form>
                            @endif
                            <form action="{{ route('admin.invites.destroy', $invite) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-secondary">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">Nenhum convite encontrado</td><tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $invites->links() }}
    </div>
</div>
@endsection