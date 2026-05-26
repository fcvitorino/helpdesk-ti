@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">Detalhes do Setor</h4>
            </div>
            <div class="card-body">
                
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{ $sector->id }}</td>
                    </tr>
                    <tr>
                        <th>Nome</th>
                        <td>{{ $sector->name }}</td>
                    </tr>
                    <tr>
                        <th>Empresa</th>
                        <td>{{ $sector->company->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Data Cadastro</th>
                        <td>{{ $sector->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Última Atualização</th>
                        <td>{{ $sector->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
                
                <a href="{{ route('admin.sectors.edit', $sector) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <a href="{{ route('admin.sectors.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection