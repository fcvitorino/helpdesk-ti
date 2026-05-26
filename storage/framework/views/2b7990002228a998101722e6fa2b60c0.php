<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperação de Senha - HelpDesk TI</title>
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
        .content {
            padding: 30px;
        }
        .button {
            display: inline-block;
            background: #ffc107;
            color: #333;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background: #e0a800;
        }
        .info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
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
            <h1>🔐 HelpDesk TI</h1>
            <p>Sistema de Chamados Interno</p>
        </div>
        
        <div class="content">
            <h2>Olá!</h2>
            
            <p>Recebemos uma solicitação para redefinir a senha da sua conta no <strong>HelpDesk TI</strong>.</p>
            
            <div class="info">
                <p><strong>📧 E-mail:</strong> <?php echo e($email); ?></p>
                <p><strong>⏰ Solicitação feita em:</strong> <?php echo e(now()->format('d/m/Y H:i')); ?></p>
            </div>
            
            <p>Clique no botão abaixo para criar uma nova senha:</p>
            
            <p style="text-align: center;">
                <a href="<?php echo e(url('/password/reset/' . $token . '?email=' . $email)); ?>" class="button">
                    🔑 Redefinir Senha
                </a>
            </p>
            
            <p style="text-align: center; font-size: 12px; color: #666;">
                Ou copie e cole o link abaixo no seu navegador:<br>
                <small><?php echo e(url('/password/reset/' . $token . '?email=' . $email)); ?></small>
            </p>
            
            <hr>
            
            <p><small>Este link expira em 60 minutos por razões de segurança.</small></p>
            
            <p><small>Se você não solicitou essa alteração, ignore este e-mail. Nenhuma alteração será feita na sua conta.</small></p>
        </div>
        
        <div class="footer">
            <p>Este é um email automático, por favor não responda.</p>
            <p>HelpDesk TI - Sistema de Chamados &copy; <?php echo e(date('Y')); ?></p>
        </div>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/auth/passwords/reset-email.blade.php ENDPATH**/ ?>