@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0">Editar Empresa</h4>
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
                
                <form method="POST" action="{{ route('admin.companies.update', $company) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" 
                               value="{{ old('name', $company->name) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">CNPJ</label>
                        <input type="text" name="cnpj" class="form-control" 
                               value="{{ old('cnpj', $company->cnpj) }}" 
                               placeholder="00.000.000/0000-00">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="text" name="telefone" class="form-control" 
                               value="{{ old('telefone', $company->telefone) }}" 
                               placeholder="(00) 0000-0000">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Endereço</label>
                        <textarea name="endereco" rows="3" class="form-control" 
                                  placeholder="Rua, número, bairro, cidade - UF">{{ old('endereco', $company->endereco) }}</textarea>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" 
                               value="1" {{ $company->is_active ? 'checked' : '' }}>
                        <label class="form-check-label">Empresa Ativa</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection