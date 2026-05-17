@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-building"></i> Detalhes da Empresa</h4>
                <div>
                    <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="{{ route('admin.companies.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">ID:</th>
                        <td>{{ $company->id }}</td>
                    </tr>
                    <tr>
                        <th>Razão Social:</th>
                        <td>{{ $company->name }}</td>
                    </tr>
                    <tr>
                        <th>CNPJ:</th>
                        <td>{{ $company->cnpj }}</td>
                    </tr>
                    <tr>
                        <th>Ramo de Atividade:</th>
                        <td>{{ $company->ramo ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Telefone:</th>
                        <td>{{ $company->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>E-mail:</th>
                        <td>{{ $company->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Endereço:</th>
                        <td>{{ $company->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($company->is_active)
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-danger">Inativo</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Data de Criação:</th>
                        <td>{{ $company->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Última Atualização:</th>
                        <td>{{ $company->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
                
                <div class="mt-4">
                    <h5><i class="bi bi-people"></i> Usuários desta Empresa ({{ $company->users->count() }})</h5>
                    @if($company->users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Perfil</th>
                                        <th>Setor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($company->users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ ucfirst($user->role) }}</td>
                                        <td>{{ $user->sector->name ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Nenhum usuário vinculado a esta empresa.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
