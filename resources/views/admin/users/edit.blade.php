@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0">Editar Usuário</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nova Senha (opcional)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Confirmar Nova Senha</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Empresa</label>
                        <select name="company_id" class="form-control" required>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ $user->company_id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Setor</label>
                        <select name="sector_id" class="form-control" required>
                            @foreach($sectors as $sector)
                                <option value="{{ $sector->id }}" {{ $user->sector_id == $sector->id ? 'selected' : '' }}>{{ $sector->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Perfil</label>
                        <select name="role" class="form-control" required>
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Usuário</option>
                            <option value="technician" {{ $user->role == 'technician' ? 'selected' : '' }}>Técnico</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection