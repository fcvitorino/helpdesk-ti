@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-tags"></i> Setores</h4>
        <a href="{{ route('admin.sectors.create') }}" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Novo Setor
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Ícone</th>
                        <th>Empresa</th>
                        <th>Chamados</th>
                        <th>Usuários</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sectors as $sector)
                    <tr>
                        <td>{{ $sector->id }}</td>
                        <td>{{ $sector->name }}</td>
                        <td>{{ $sector->description ?? '-' }}</td>
                        <td>{{ $sector->icon ?? '-' }}</td>
                        <td>{{ $sector->company->name ?? '-' }}</td>
                        <td>{{ $sector->tickets->count() }}</td>
                        <td>{{ $sector->users->count() }}</td>
                        <td>
                            <a href="{{ route('admin.sectors.show', $sector) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.sectors.edit', $sector) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.sectors.destroy', $sector) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $sectors->links() }}
    </div>
</div>
@endsection