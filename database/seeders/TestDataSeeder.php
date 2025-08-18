<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Usando o Query Builder para uma inserção mais direta e evitar cache de schema
        DB::table('usuarios')->updateOrInsert(
            ['email' => 'admin@progmud.com.br'],
            [
                'nome' => 'Admin',
                'sobrenome' => 'Progmud',
                'password' => Hash::make('password'), // Altere para uma senha segura
                'funcao' => 'admin',
                'status' => 'ativo',
                'termo_aceite' => true,
                'data_aceite' => now(),
                'ip_aceite' => '127.0.0.1',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
