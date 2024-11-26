<?php

namespace Database\Factories;

use App\Models\Contato;
use App\Models\Endereco;
use App\Models\Habilidade;
use App\Models\User;
use App\Models\Usuario;
use App\Models\Voluntario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voluntario>
 */
class VoluntarioFactory extends Factory
{
    protected $model = Voluntario::class;

    public function definition(): array
    {
        return [
            'id_usuario' => User::factory(), // Criando um usuário dinamicamente
            'id_contato' => Contato::factory(), // Criando um contato dinamicamente
            'id_endereco' => Endereco::factory(), // Criando um endereço dinamicamente
            'cpf_voluntario' => $this->faker->unique()->numerify('###.###.###-##'), // Gerando CPF fictício
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Voluntario $voluntario) {
            // Associando habilidades ao voluntário
            $habilidades = Habilidade::inRandomOrder()->take(5)->get();

            foreach ($habilidades as $habilidade) {
                $voluntario->habilidades()->attach($habilidade->id_habilidade);
            }
        });
    }
}
