@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Abrir Novo Chamado</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Título do Chamado *</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Onde o problema ocorre? *</label>
                        <select class="form-select" id="location" name="location" required>
                            <option value="">Selecione...</option>
                            <option value="navegador">🌐 Navegador</option>
                            <option value="erp">📊 Sistema ERP</option>
                            <option value="rede">🌍 Rede</option>
                            <option value="hardware">💻 Hardware</option>
                            <option value="perifericos">🖱️ Periféricos</option>
                            <option value="impressoras">🖨️ Impressoras</option>
                            <option value="outros">📌 Outros</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="priority" class="form-label">Prioridade *</label>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="normal">🟢 Normal</option>
                            <option value="urgente">🔴 Urgente</option>
                            <option value="baixa">⚪ Baixa</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição do Problema *</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="attachments" class="form-label">Anexar arquivos</label>
                        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple accept="image/*,.jpg,.jpeg,.png,.gif,.bmp,.webp,.pdf,.doc,.docx,.xls,.xlsx,.zip">
                        <small class="text-muted">Anexar arquivos (imagens JPG, PNG, GIF, BMP, WEBP, PDF, DOC, XLS, ZIP) - Máx 5MB por arquivo</small>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Abrir Chamado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection