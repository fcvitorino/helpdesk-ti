<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    public function run(): void
    {
        $sectors = [
            ['name' => 'Tecnologia da Informação', 'description' => 'Suporte de TI e infraestrutura', 'icon' => 'bi-pc'],
            ['name' => 'Recursos Humanos', 'description' => 'Departamento pessoal e benefícios', 'icon' => 'bi-people'],
            ['name' => 'Financeiro', 'description' => 'Contas a pagar/receber e orçamentos', 'icon' => 'bi-calculator'],
            ['name' => 'Comercial', 'description' => 'Vendas e relacionamento com clientes', 'icon' => 'bi-graph-up'],
            ['name' => 'Marketing', 'description' => 'Publicidade e redes sociais', 'icon' => 'bi-megaphone'],
            ['name' => 'Produção', 'description' => 'Operações e manufatura', 'icon' => 'bi-gear'],
            ['name' => 'Logística', 'description' => 'Estoque e entregas', 'icon' => 'bi-truck'],
        ];

        foreach ($sectors as $sector) {
            Sector::create($sector);
        }
    }
}   