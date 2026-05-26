@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Usuário</h4>
            </div>
            <div class="card-body">
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">E-mail <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nova Senha</label>
                                <input type="password" name="password" class="form-control" placeholder="Deixe em branco para manter a atual">
                                <small class="text-muted d-block mt-1">
                                    ⚠️ Se desejar alterar: mínimo 8 caracteres, 1 letra MAIÚSCULA e 1 caractere especial (! @ # $ % & * ?)
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Confirmar Nova Senha</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Empresa <span class="text-danger">*</span></label>
                        <select name="company_id" id="company_id" class="form-select" required>
                            <option value="">Selecione uma empresa</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $user->company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Setor <span class="text-danger">*</span></label>
                        <select name="sector_id" id="sector_id" class="form-select" required>
                            <option value="">Selecione um setor</option>
                            @foreach($sectors as $sector)
                                <option value="{{ $sector->id }}" {{ old('sector_id', $user->sector_id) == $sector->id ? 'selected' : '' }}>
                                    {{ $sector->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Perfil <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>👤 Usuário</option>
                            <option value="technician" {{ old('role', $user->role) == 'technician' ? 'selected' : '' }}>🔧 Técnico</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>👑 Administrador</option>
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Atualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('company_id').addEventListener('change', function() {
        var companyId = this.value;
        var sectorSelect = document.getElementById('sector_id');
        var currentSector = '{{ $user->sector_id }}';
        
        if (companyId) {
            sectorSelect.innerHTML = '<option value="">🔃 Carregando...</option>';
            sectorSelect.disabled = true;
            
            fetch('{{ url("/admin/sectors/by-company") }}/' + companyId)
                .then(response => response.json())
                .then(data => {
                    sectorSelect.innerHTML = '<option value="">📋 Selecione um setor</option>';
                    data.forEach(sector => {
                        var option = document.createElement('option');
                        option.value = sector.id;
                        option.textContent = sector.name;
                        if (sector.id == currentSector) {
                            option.selected = true;
                        }
                        sectorSelect.appendChild(option);
                    });
                    sectorSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Erro:', error);
                    sectorSelect.innerHTML = '<option value="">❌ Erro ao carregar setores</option>';
                    sectorSelect.disabled = false;
                });
        } else {
            sectorSelect.innerHTML = '<option value="">🏢 Primeiro selecione uma empresa</option>';
            sectorSelect.disabled = false;
        }
    });
</script>
@endsection