<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Notificação - HelpDesk TI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .content h2 {
            color: #333;
            margin-top: 0;
        }
        .info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .button {
            display: inline-block;
            background: #4a90e2;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background: #357abd;
        }
        .footer {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 HelpDesk TI</h1>
            <p>Sistema de Chamados Interno</p>
        </div>
        
        <div class="content">
            <h2>{{ $title ?? 'Notificação' }}</h2>
            
            <p>Olá, <strong>{{ $user->name ?? 'Usuário' }}</strong>!</p>
            
            <div class="info">
                <p style="margin: 0;">{{ $message ?? 'Você tem uma nova notificação.' }}</p>
            </div>
            
            <p style="text-align: center;">
                <a href="{{ url('/tickets/' . ($ticket->id ?? 1)) }}" class="button">
                    🔍 Visualizar Chamado #{{ $ticket->ticket_number ?? 'N/A' }}
                </a>
            </p>
            
            <hr>
            
            <small>Este é um email automático, por favor não responda.</small>
        </div>
        
        <div class="footer">
            <p>HelpDesk TI - Sistema de Chamados &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>