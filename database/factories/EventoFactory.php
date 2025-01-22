<?php

namespace Database\Factories;

use App\Models\Evento;
use App\Models\Habilidade;
use App\Models\Voluntario;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventoFactory extends Factory
{
    protected $model = Evento::class;

    public function definition(): array
    {
        return [
            'descricao_evento' => $this->faker->sentence(),
            'data_hora_evento' => $this->faker->dateTimeBetween('now', '+1 year'),
            'id_instituicao' => 1, // Instituição fixa para teste
            'id_endereco' => $this->faker->numberBetween(1, 10), // IDs de endereços válidos
            'data_hora_limite_evento' => $this->faker->dateTimeBetween('now', '+1 year'),
            'nome_evento' => $this->faker->words(3, true),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Evento $evento) {
            // Adiciona um relacionamento com vários voluntários
            $voluntarios = Voluntario::inRandomOrder()
                ->take(rand(10, 50)) // Aumenta o número de voluntários por evento
                ->pluck('id_voluntario');

            foreach ($voluntarios as $id_voluntario) {
                $createdAt = $this->faker->dateTimeBetween('-1 year', 'now');
                $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');

                $evento->voluntarios()->attach($id_voluntario, [
                    'habilidade_voluntario' => $this->faker->randomElement([
                        'Comunicação',
                        'Cuidados',
                        'Educação',
                        'Culinária',
                        'Música',
                        'Dança',
                        'Administração',
                        'Animais',
                        'Marketing',
                        'TI',
                        'Pesquisa',
                        'Artes Marciais',
                        'Enfermagem',
                        'Medicina',
                    ]),
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);
            }

            // Relaciona habilidades com o evento
            $habilidadesExistentes = Habilidade::inRandomOrder()->take(rand(1, 5))->get();

            foreach ($habilidadesExistentes as $habilidade) {
                $evento->habilidades()->attach($habilidade->id_habilidade, [
                    'meta_evento' => $this->faker->numberBetween(10, 100),
                    'quantidade_voluntario' => $this->faker->numberBetween(5, 30),
                ]);
            }
        });
    }
}

