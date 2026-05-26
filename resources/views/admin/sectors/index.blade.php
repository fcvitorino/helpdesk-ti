@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Setores</h4>
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
                        <th>Empresa</th>
                        <th>Data Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sectors as $sector)
                    <tr>
                        <td>{{ $sector->id }}</td>
                        <td>{{ $sector->name }}</td>
                        <td>{{ $sector->company->name ?? '-' }}</td>
                        <td>{{ $sector->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.sectors.show', $sector) }}" class="btn btn-sm btn-info" title="Visualizar">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.sectors.edit', $sector) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.sectors.destroy', $sector) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Nenhum setor encontrado</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $sectors->links() }}
    </div>
</div>
@endsection