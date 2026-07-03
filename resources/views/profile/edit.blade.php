@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-person-circle"></i> Meu Perfil</h4>
            </div>
            <div class="card-body">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Senha Atual</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nova Senha</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted d-block mt-1">
                            ⚠️ Mínimo 8 caracteres, 1 letra MAIÚSCULA e 1 caractere especial (! @ # $ % & * ?)
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Confirmar Nova Senha</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Alterar Senha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection