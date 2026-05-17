<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Convite - HelpDesk TI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .button {
            display: inline-block;
            background-color: #0d6efd;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>HelpDesk TI</h2>
            <p>Sistema de Chamados</p>
        </div>
        <div class="content">
            <h3>Olá, {{ $invite->name }}!</h3>
            <p>Você foi convidado para acessar o sistema de chamados <strong>HelpDesk TI</strong>.</p>
            
            <div class="info">
                <p><strong>📋 Seus dados de acesso:</strong></p>
                <p>🔹 <strong>Empresa:</strong> {{ $invite->company->name ?? 'N/A' }}</p>
                <p>🔹 <strong>Setor:</strong> {{ $invite->sector->name ?? 'N/A' }}</p>
                <p>🔹 <strong>Perfil:</strong> 
                    @if($invite->role == 'admin')
                        Administrador
                    @elseif($invite->role == 'technician')
                        Técnico de Suporte
                    @else
                        Usuário Comum
                    @endif
                </p>
            </div>
            
            <p>Para ativar sua conta e definir sua senha, clique no botão abaixo:</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/accept-invite/' . $invite->token) }}" class="button">
                    🔗 Aceitar Convite
                </a>
            </div>
            
            <p>Este link expira em <strong>{{ $invite->expires_at->format('d/m/Y') }}</strong>.</p>
            
            <p style="font-size: 14px; color: #666;">
                Caso não tenha solicitado este convite, ignore este email.
            </p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} HelpDesk TI - Todos os direitos reservados</p>
            <p>Este é um email automático, por favor não responda.</p>
        </div>
    </div>
</body>
</html>