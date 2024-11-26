<?php

namespace Database\Factories;

use App\Models\Endereco;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Endereco>
 */
class EnderecoFactory extends Factory
{
    protected $model = Endereco::class;

    public function definition(): array
    {
        return [
            'cep_endereco' => $this->faker->numerify('#####-##'),
            'complemento_endereco' => $this->faker->randomElement(['Apto ' . $this->faker->buildingNumber(), 'Casa', 'Loja']),
            'cidade_endereco' => $this->faker->city(),
            'logradouro_endereco' => $this->faker->streetName(),
            'estado_endereco' => $this->faker->stateAbbr(),
            'bairro_endereco' => $this->faker->streetSuffix(),
            'numero_endereco' => $this->faker->buildingNumber(),
        ];
    }
}
