@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-tag"></i> Detalhes do Setor</h4>
                <div>
                    <a href="{{ route('admin.sectors.edit', $sector) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="{{ route('admin.sectors.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">ID:</th>
                        <td>{{ $sector->id }}</td>
                    </tr>
                    <tr>
                        <th>Empresa:</th>
                        <td>{{ $sector->company->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nome:</th>
                        <td>{{ $sector->name }}</td>
                    </tr>
                    <tr>
                        <th>Descrição:</th>
                        <td>{{ $sector->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Ícone:</th>
                        <td>
                            @if($sector->icon)
                                <i class="{{ $sector->icon }}"></i> {{ $sector->icon }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Total de Chamados:</th>
                        <td>{{ $sector->tickets->count() }}</td>
                    </tr>
                    <tr>
                        <th>Total de Usuários:</th>
                        <td>{{ $sector->users->count() }}</td>
                    </tr>
                    <tr>
                        <th>Data de Criação:</th>
                        <td>{{ $sector->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Última Atualização:</th>
                        <td>{{ $sector->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection