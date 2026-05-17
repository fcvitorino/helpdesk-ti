@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-people"></i> Usuários</h4>
        <a href="{{ route('admin.users.create') }}" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Novo Usuário
        </a>
    </div>
    <div class="card-body">
        
        <!-- MENSAGENS DE SUCESSO OU ERRO -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- FORMULÁRIO DE FILTROS -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
            <div class="row g-3">
                
                <!-- CAMPO DE PESQUISA -->
                <div class="col-md-4">
                    <label class="form-label">Pesquisar</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Digite nome ou email..." 
                           value="{{ request('search') }}">
                </div>
                
                <!-- FILTRO POR PERFIL -->
                <div class="col-md-3">
                    <label class="form-label">Perfil</label>
                    <select name="role" class="form-select">
                        <option value="">Todos</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="technician" {{ request('role') == 'technician' ? 'selected' : '' }}>Técnico</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Usuário</option>
                    </select>
                </div>
                
                <!-- FILTRO POR STATUS - É AQUI QUE VOCÊ SELECIONA INATIVOS! -->
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ Ativos</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>❌ Inativos</option>
                    </select>
                </div>
                
                <!-- BOTÕES -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        🔍 Filtrar
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        🗑️ Limpar
                    </a>
                </div>
            </div>
        </form>

        <!-- CONTADOR DE RESULTADOS -->
        <div class="mb-3">
            <strong>📊 Total encontrado: {{ $users->total() }} usuário(s)</strong>
        </div>

        <!-- TABELA DE USUÁRIOS -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Empresa</th>
                        <th>Setor</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th>Data Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="{{ $user->trashed() ? 'table-danger' : '' }}">
                        <td>{{ $user->id }}</td>
                        <td>
                            {{ $user->name }}
                            @if($user->trashed())
                                <span class="badge bg-danger">(Inativo)</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->company->name ?? '-' }}</td>
                        <td>{{ $user->sector->name ?? '-' }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @elseif($user->role == 'technician')
                                <span class="badge bg-info">Técnico</span>
                            @else
                                <span class="badge bg-secondary">Usuário</span>
                            @endif
                        </td>
                        <td>
                            @if($user->trashed())
                                <span class="badge bg-danger">❌ Inativo</span>
                            @else
                                <span class="badge bg-success">✅ Ativo</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <!-- BOTÃO EDITAR (só aparece se estiver ativo) -->
                            @if(!$user->trashed())
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning" title="Editar">
                                    ✏️
                                </a>
                            @endif
                            
                            <!-- BOTÃO REATIVAR (aparece se estiver inativo) -->
                            @if($user->trashed())
                                <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success" title="Reativar" onclick="return confirm('Reativar este usuário?')">
                                        🔄 Reativar
                                    </button>
                                </form>
                            @else
                                <!-- BOTÃO DESATIVAR (aparece se estiver ativo) -->
                                <form action="{{ route('admin.users.deactivate', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-warning" title="Desativar" onclick="return confirm('Desativar este usuário?')">
                                        ⚠️ Desativar
                                    </button>
                                </form>
                            @endif
                            
                            <!-- BOTÃO EXCLUIR PERMANENTEMENTE (só aparece se estiver inativo) -->
                            @if($user->id !== auth()->id() && $user->trashed())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Excluir Permanentemente" onclick="return confirm('Excluir permanentemente? Isso NÃO pode ser desfeito!')">
                                        🗑️ Excluir
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                📭 Nenhum usuário encontrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- LINKS DE PAGINAÇÃO -->
        <div class="mt-3">
            {{ $users->links() }}
        </div>
        
    </div>
</div>
@endsection