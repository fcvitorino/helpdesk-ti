<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "Testando envio de email...\n";

try {
    Mail::raw('Teste de email do HelpDesk TI', function ($message) {
        $message->to('seuemail@teste.com');
        $message->subject('Teste Direto');
    });
    echo "✅ Email enviado com sucesso!\n";
} catch (\Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}