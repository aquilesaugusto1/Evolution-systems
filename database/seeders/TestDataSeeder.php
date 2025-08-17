<?php

namespace Database\Seeders;

use App\Models\EmpresaParceira;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar um usuário Admin Padrão
        User::factory()->create([
            'nome' => 'Admin Progmud',
            'email' => 'admin@progmud.com.br',
            'password' => Hash::make('password'),
            'funcao' => 'admin',
            'status' => 'Ativo',
            'termo_aceite' => true,
        ]);

        // Criar Coordenadores
        User::factory()->create([
            'nome' => 'Carlos Coordenador',
            'email' => 'carlos.coordenador@progmud.com.br',
            'password' => Hash::make('password'),
            'funcao' => 'coordenador_operacoes',
            'status' => 'Ativo',
            'termo_aceite' => true,
        ]);
        User::factory()->create([
            'nome' => 'Ana Coordenadora',
            'email' => 'ana.coordenadora@progmud.com.br',
            'password' => Hash::make('password'),
            'funcao' => 'coordenador_tecnico',
            'status' => 'Ativo',
            'termo_aceite' => true,
        ]);

        // Criar Tech Leads
        $techleads = User::factory()->count(5)->sequence(
            ['nome' => 'Mariana Oliveira', 'email' => 'mariana.oliveira@progmud.com.br'],
            ['nome' => 'Rafael Souza', 'email' => 'rafael.souza@progmud.com.br'],
            ['nome' => 'Beatriz Lima', 'email' => 'beatriz.lima@progmud.com.br'],
            ['nome' => 'Gustavo Pereira', 'email' => 'gustavo.pereira@progmud.com.br'],
            ['nome' => 'Juliana Costa', 'email' => 'juliana.costa@progmud.com.br'],
        )->create([
            'password' => Hash::make('password'),
            'funcao' => 'techlead',
            'status' => 'Ativo',
            'termo_aceite' => true,
        ]);

        // Criar Consultores
        $consultores = User::factory()->count(15)->create([
            'password' => Hash::make('password'),
            'funcao' => 'consultor',
            'status' => 'Ativo',
            'termo_aceite' => true,
        ]);

        // Associar alguns consultores a Tech Leads
        $techleads->each(function ($techlead) use ($consultores) {
            $consultoresLiderados = $consultores->random(3);
            $techlead->consultoresLiderados()->attach($consultoresLiderados->pluck('id'));
        });

        // Criar Empresas Clientes
        EmpresaParceira::factory()->count(25)->create();
    }
}
