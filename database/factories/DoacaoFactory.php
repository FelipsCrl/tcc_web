<?php

namespace Database\Factories;

use App\Models\Doacao;
use App\Models\Instituicao;
use App\Models\Voluntario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doacao>
 */
class DoacaoFactory extends Factory
{
    protected $model = Doacao::class;

    public function definition(): array
    {
        return [
            'id_instituicao' => Instituicao::factory(), // Associa uma nova instituição
            'observacao_doacao' => $this->faker->sentence,
            'data_hora_limite_doacao' => $this->faker->dateTimeBetween('now', '+1 year'),
            'nome_doacao' => $this->faker->word,
            'coleta_doacao' => $this->faker->boolean,
            'card_doacao' => $this->faker->boolean,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Doacao $doacao) {
            // Associa o voluntário à doação
            $voluntarios = Voluntario::inRandomOrder()->take(10)->get(); // Pegue 10 voluntários existentes aleatoriamente

            foreach ($voluntarios as $voluntario) {
                $doacao->voluntarios()->attach($voluntario->id_voluntario, [
                    'situacao_solicitacao_doacao' => $this->faker->randomElement([-1, 0, 1]),
                    'data_hora_coleta' => Carbon::now()->addDays($this->faker->numberBetween(1, 10)),
                    'categoria_doacao' => $this->faker->sentence(2),
                    'quantidade_doacao' => $this->faker->numberBetween(1, 30),
                ]);
            }
        });
    }
}
