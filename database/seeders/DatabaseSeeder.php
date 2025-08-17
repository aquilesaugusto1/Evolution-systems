<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Este método irá chamar todos os seeders que listarmos aqui.
        // Por enquanto, chamaremos apenas o nosso seeder de teste.
        $this->call([
            TestDataSeeder::class,
        ]);
    }
}
