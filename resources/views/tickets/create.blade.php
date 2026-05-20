@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Abrir Chamado</h4>
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
                    
                    <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Título do Chamado <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title') }}" required autofocus>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Local do Problema <span class="text-danger">*</span></label>
                            <select name="location" class="form-select @error('location') is-invalid @enderror" required>
                                <option value="">Selecione...</option>
                                <option value="navegador" {{ old('location') == 'navegador' ? 'selected' : '' }}>🌐 Navegador</option>
                                <option value="rede" {{ old('location') == 'rede' ? 'selected' : '' }}>🔌 Rede</option>
                                <option value="hardware" {{ old('location') == 'hardware' ? 'selected' : '' }}>💻 Hardware</option>
                                <option value="impressora" {{ old('location') == 'impressora' ? 'selected' : '' }}>🖨️ Impressora</option>
                                <option value="perifericos" {{ old('location') == 'perifericos' ? 'selected' : '' }}>🖱️ Periféricos</option>
                                <option value="monitor" {{ old('location') == 'monitor' ? 'selected' : '' }}>🖥️ Monitor</option>
                                <option value="outros" {{ old('location') == 'outros' ? 'selected' : '' }}>📦 Outros</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Prioridade <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="baixa" {{ old('priority') == 'baixa' ? 'selected' : '' }}>🔵 Baixa</option>
                                <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>🟢 Normal</option>
                                <option value="alta" {{ old('priority') == 'alta' ? 'selected' : '' }}>🟠 Alta</option>
                                <option value="urgente" {{ old('priority') == 'urgente' ? 'selected' : '' }}>🔴 Urgente</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Descrição do Problema <span class="text-danger">*</span></label>
                            <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Descreva detalhadamente o problema..." required>{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Anexar Arquivos (opcional)</label>
                            <input type="file" name="attachments[]" class="form-control" multiple>
                            <small class="text-muted">Você pode anexar imagens, PDF ou documentos até 5MB</small>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Abrir Chamado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection