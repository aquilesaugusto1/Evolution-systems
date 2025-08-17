<?php

namespace Database\Factories;

use App\Models\EmpresaParceira;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmpresaParceira>
 */
class EmpresaParceiraFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmpresaParceira::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome_empresa' => $this->faker->company,
            'cnpj' => $this->faker->unique()->numerify('##.###.###/####-##'),
            'status' => $this->faker->randomElement(['Ativo', 'Inativo']),
            'saldo_horas' => $this->faker->randomFloat(2, 20, 200),
            // Adicione outros campos que sua tabela possa ter e que não podem ser nulos
        ];
    }
}
