<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aceitar Convite - HelpDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Aceitar Convite</h4>
                    </div>
                    <div class="card-body">
                        <h5>Olá, {{ $invite->name }}!</h5>
                        <p>Você foi convidado para acessar o sistema HelpDesk TI.</p>
                        
                        <div class="alert alert-info">
                            <strong>Empresa:</strong> {{ $invite->company->name }}<br>
                            <strong>Setor:</strong> {{ $invite->sector->name }}<br>
                            <strong>Perfil:</strong> 
                            @if($invite->role == 'admin') Administrador
                            @elseif($invite->role == 'technician') Técnico
                            @else Usuário Comum
                            @endif
                        </div>
                        
                        <form method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="password" class="form-label">Defina sua senha</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirme sua senha</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Ativar Conta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>