@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="bi bi-building"></i> Selecione uma Empresa
                    </h3>
                    <small>Você precisa selecionar uma empresa para continuar</small>
                </div>
                <div class="card-body p-4">
                    
                    @if(session('warning'))
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> {{ session('warning') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('company.select.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Empresa</label>
                            <select name="company_id" class="form-select form-select-lg" required>
                                <option value="">Selecione uma empresa...</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-check-circle"></i> Acessar Sistema
                        </button>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Você pode trocar de empresa a qualquer momento no menu superior.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection