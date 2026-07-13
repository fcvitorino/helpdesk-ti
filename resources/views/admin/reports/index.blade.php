@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Relatório de Chamados</h5>
                </div>
                <div class="card-body">
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.reports.generate') }}" target="_blank">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Data Início <span class="text-danger">*</span></label>
                                <input type="date" name="data_inicio" class="form-control @error('data_inicio') is-invalid @enderror" 
                                       value="{{ old('data_inicio', date('Y-m-01')) }}" required>
                                @error('data_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Data Fim <span class="text-danger">*</span></label>
                                <input type="date" name="data_fim" class="form-control @error('data_fim') is-invalid @enderror" 
                                       value="{{ old('data_fim', date('Y-m-d')) }}" required>
                                @error('data_fim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            O relatório será gerado em PDF com:
                            <ul class="mb-0 mt-2">
                                <li>📊 Total de chamados no período</li>
                                <li>📈 Distribuição por status (Aberto, Em Andamento, Resolvido, Fechado)</li>
                                <li>🔴 Distribuição por prioridade (Baixa, Normal, Alta, Urgente)</li>
                                <li>🏢 Distribuição por setor</li>
                                <li>👤 Top 10 usuários que mais abriram chamados</li>
                                <li>⏱️ Tempo médio de resolução (horas)</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-file-pdf"></i> Gerar PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection