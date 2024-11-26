<?php

namespace Database\Factories;

use App\Models\Evento;
use App\Models\Habilidade;
use App\Models\Voluntario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evento>
 */
class EventoFactory extends Factory
{
    protected $model = Evento::class;

    public function definition(): array
    {
        return [
            'descricao_evento' => $this->faker->sentence(),
            'data_hora_evento' => $this->faker->dateTimeBetween('now', '+1 year'),
            'id_instituicao' => $this->faker->numberBetween(1, 10), // Assumindo IDs de instituições válidos
            'id_endereco' => $this->faker->numberBetween(1, 10), // Assumindo IDs de endereços válidos
            'data_hora_limite_evento' => $this->faker->dateTimeBetween('now', '+1 year'),
            'nome_evento' => $this->faker->words(3, true)
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Evento $evento) {
            // Adiciona um relacionamento com um voluntário existente
            $voluntarios = Voluntario::inRandomOrder()->take(rand(1, 20))->pluck('id_voluntario');

            foreach ($voluntarios as $id_voluntario) {
                $evento->voluntarios()->attach($id_voluntario, [
                    'habilidade_voluntario' => $this->faker->randomElement([
                        'Comunicação',
                        'Cuidados',
                        'Educação',
                        'Culinária',
                        'Música',
                        'Dança',
                        'Administração',
                        'Bem-estar Animal',
                        'Marketing',
                        'TI',
                        'Pesquisa',
                        'Artes Marciais',
                        'Enfermagem',
                        'Medicina'
                    ])
                ]);
            }

            // Relaciona habilidades com eventos existentes
            $habilidadesExistentes = Habilidade::inRandomOrder()->take(3)->get(); // Limita a 3 habilidades por evento

            foreach ($habilidadesExistentes as $habilidade) {
                $evento->habilidades()->attach($habilidade->id_habilidade, [
                    'meta_evento' => $this->faker->numberBetween(10, 100),
                    'quantidade_voluntario' => $this->faker->numberBetween(1, 50),
                ]);
            }

        });
    }

}
