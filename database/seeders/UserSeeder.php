<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuário Administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@helpdesk.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'sector_id' => 1, // TI
        ]);

        // Usuário Técnico
        User::create([
            'name' => 'Técnico Suporte',
            'email' => 'tecnico@helpdesk.com',
            'password' => Hash::make('12345678'),
            'role' => 'technician',
            'sector_id' => 1, // TI
        ]);

        // Usuário Comum
        User::create([
            'name' => 'Usuário Comum',
            'email' => 'usuario@helpdesk.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'sector_id' => 2, // RH
        ]);
    }
}