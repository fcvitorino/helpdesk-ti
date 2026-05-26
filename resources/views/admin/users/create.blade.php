@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="bi bi-person-plus"></i> Novo Usuário</h4>
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
                
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">E-mail <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Senha <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required>
                                <small class="text-muted d-block mt-1">
                                    ⚠️ Mínimo 8 caracteres, 1 letra MAIÚSCULA e 1 caractere especial (! @ # $ % & * ?)
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Confirmar Senha <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Empresa <span class="text-danger">*</span></label>
                        <select name="company_id" id="company_id" class="form-select" required>
                            <option value="">Selecione uma empresa</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Setor <span class="text-danger">*</span></label>
                        <select name="sector_id" id="sector_id" class="form-select" required>
                            <option value="">Primeiro selecione uma empresa</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Perfil <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>👤 Usuário</option>
                            <option value="technician" {{ old('role') == 'technician' ? 'selected' : '' }}>🔧 Técnico</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>👑 Administrador</option>
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Salvar Usuário
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Função para carregar setores via AJAX
    document.getElementById('company_id').addEventListener('change', function() {
        var companyId = this.value;
        var sectorSelect = document.getElementById('sector_id');
        
        if (companyId) {
            sectorSelect.innerHTML = '<option value="">🔃 Carregando setores...</option>';
            sectorSelect.disabled = true;
            
            fetch('{{ url("/admin/sectors/by-company") }}/' + companyId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.json();
                })
                .then(data => {
                    sectorSelect.innerHTML = '<option value="">📋 Selecione um setor</option>';
                    if (data.length === 0) {
                        sectorSelect.innerHTML = '<option value="">⚠️ Nenhum setor cadastrado</option>';
                    }
                    data.forEach(sector => {
                        var option = document.createElement('option');
                        option.value = sector.id;
                        option.textContent = sector.name;
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
    
    document.addEventListener('DOMContentLoaded', function() {
        var companyId = document.getElementById('company_id').value;
        if (companyId) {
            var event = new Event('change');
            document.getElementById('company_id').dispatchEvent(event);
        }
    });
</script>
@endsection