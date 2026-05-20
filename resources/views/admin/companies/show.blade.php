@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">Detalhes da Empresa</h4>
            </div>
            <div class="card-body">
                
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{ $company->id }}</td>
                    </tr>
                    <tr>
                        <th>Nome</th>
                        <td>{{ $company->name }}</td>
                    </tr>
                    <tr>
                        <th>CNPJ</th>
                        <td>{{ $company->cnpj ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Telefone</th>
                        <td>{{ $company->telefone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Endereço</th>
                        <td>{{ $company->endereco ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($company->is_active)
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-danger">Inativo</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Data Cadastro</th>
                        <td>{{ $company->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
                
                <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection