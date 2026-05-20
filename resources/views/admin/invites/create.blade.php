@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Novo Convite</h4>
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
                
                <form method="POST" action="{{ route('admin.invites.store') }}" id="inviteForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">E-mail <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" 
                               value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Perfil <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="user">Usuário</option>
                            <option value="technician">Técnico</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Empresa <span class="text-danger">*</span></label>
                        <select name="company_id" id="company_id" class="form-select" required>
                            <option value="">Selecione...</option>
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
                    
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-envelope"></i> Enviar Convite
                    </button>
                    
                    <a href="{{ route('admin.invites.index') }}" class="btn btn-secondary w-100 mt-2">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
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
            // Limpar e desabilitar o select enquanto carrega
            sectorSelect.innerHTML = '<option value="">Carregando...</option>';
            sectorSelect.disabled = true;
            
            // Buscar setores via AJAX
            fetch('{{ url("/admin/sectors/by-company") }}/' + companyId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.json();
                })
                .then(data => {
                    sectorSelect.innerHTML = '<option value="">Selecione um setor</option>';
                    if (data.length === 0) {
                        sectorSelect.innerHTML = '<option value="">Nenhum setor cadastrado</option>';
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
                    console.error('Erro ao carregar setores:', error);
                    sectorSelect.innerHTML = '<option value="">Erro ao carregar setores</option>';
                    sectorSelect.disabled = false;
                });
        } else {
            sectorSelect.innerHTML = '<option value="">Primeiro selecione uma empresa</option>';
            sectorSelect.disabled = false;
        }
    });
    
    // Se já tiver uma empresa selecionada (ex: após erro de validação)
    document.addEventListener('DOMContentLoaded', function() {
        var companyId = document.getElementById('company_id').value;
        if (companyId) {
            var event = new Event('change');
            document.getElementById('company_id').dispatchEvent(event);
        }
    });
</script>
@endsection