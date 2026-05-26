<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aceitar Convite - HelpDesk TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white text-center py-3">
                        <h4 class="mb-0">
                            <i class="bi bi-envelope-check"></i> Aceitar Convite
                        </h4>
                        <small>HelpDesk TI - Sistema de Chamados</small>
                    </div>
                    <div class="card-body p-4">
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Você foi convidado!</strong><br>
                            📧 Email: {{ $invite->email }}<br>
                            👔 Perfil: {{ ucfirst($invite->role) }}<br>
                            🏢 Empresa: {{ $invite->company->name ?? '-' }}<br>
                            📂 Setor: {{ $invite->sector->name ?? '-' }}
                        </div>
                        
                        <form method="POST" action="{{ route('invite.register', $invite->token) }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Nome Completo</label>
                                <input type="text" name="name" class="form-control" required autofocus>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Senha</label>
                                <input type="password" name="password" class="form-control" required>
                                <small class="text-muted">
                                    ⚠️ A senha deve ter no mínimo 8 caracteres, 1 letra MAIÚSCULA e 1 caractere especial (! @ # $ % & * ?)
                                </small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Confirmar Senha</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Criar Conta e Acessar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>