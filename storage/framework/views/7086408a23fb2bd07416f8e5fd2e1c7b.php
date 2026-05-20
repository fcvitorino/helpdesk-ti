<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Convite HelpDesk TI</title>
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
            background: #4a90e2;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button:hover {
            background: #357abd;
        }
        .info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
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
            <h1>🎫 HelpDesk TI</h1>
            <p>Sistema de Chamados Interno</p>
        </div>
        
        <div class="content">
            <h2>Olá, você foi convidado!</h2>
            
            <p>Um administrador convidou você para acessar o sistema de chamados <strong>HelpDesk TI</strong>.</p>
            
            <div class="info">
                <p><strong>📧 Seu email:</strong> <?php echo e($invite->email); ?></p>
                <p><strong>👔 Perfil:</strong> 
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invite->role == 'admin'): ?>
                        Administrador
                    <?php elseif($invite->role == 'technician'): ?>
                        Técnico
                    <?php else: ?>
                        Usuário
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
                <p><strong>🏢 Empresa:</strong> <?php echo e($invite->company->name ?? 'Não definida'); ?></p>
                <p><strong>📅 Expira em:</strong> <?php echo e($invite->expires_at->format('d/m/Y')); ?></p>
            </div>
            
            <p>Para aceitar o convite e criar sua senha, clique no botão abaixo:</p>
            
            <p style="text-align: center;">
                <a href="<?php echo e(url('register/' . $invite->token)); ?>" class="button" style="background: #28a745; color: white; display: inline-block; padding: 12px 24px; text-decoration: none; border-radius: 5px;">
                    ✅ Aceitar Convite
                </a>
            </p>
            
            <p style="text-align: center; font-size: 12px; color: #666;">
                Ou copie e cole o link abaixo no seu navegador:<br>
                <small><?php echo e(url('register/' . $invite->token)); ?></small>
            </p>
            
            <hr>
            
            <p><small>Este link expira em 7 dias.</small></p>
        </div>
        
        <div class="footer">
            <p>Este é um email automático, por favor não responda.</p>
            <p>HelpDesk TI - Sistema de Chamados &copy; <?php echo e(date('Y')); ?></p>
        </div>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\helpdesk\resources\views/emails/invite.blade.php ENDPATH**/ ?>