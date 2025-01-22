<?php

namespace Database\Factories;

use App\Models\Contato;
use App\Models\Doacao;
use App\Models\Endereco;
use App\Models\Instituicao;
use App\Models\User;
use App\Models\Voluntario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instituicao>
 */
class InstituicaoFactory extends Factory
{
    protected $model = Instituicao::class;

    public function definition(): array
    {
        return [
            'id_usuario' => User::factory(), // Criando um usuário dinamicamente
            'id_contato' => Contato::factory(), // Criando um contato dinamicamente
            'id_endereco' => Endereco::factory(), // Criando um endereço dinamicamente
            'descricao_instituicao' => $this->faker->sentence(),
            'funcionamento_instituicao' => json_encode($this->generateFuncionamento()),
            'cnpj_instituicao' => $this->faker->unique()->numerify('##.###.###/####-##'),
        ];
    }

    protected function generateFuncionamento()
    {
        $diasDaSemana = [
            'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'
        ];

        $funcionamento = [];

        foreach ($diasDaSemana as $dia) {
            $funciona = $this->faker->boolean; // Aleatoriamente true ou false
            $funcionamento[$dia] = [
                'abertura' => $funciona ? $this->faker->time('H:i') : null, // Gera hora aleatória se funciona
                'fechamento' => $funciona ? $this->faker->time('H:i') : null, // Gera hora aleatória se funciona
                'funciona' => $funciona,
            ];
        }

        return $funcionamento;
    }

    public function configure()
    {
        return $this->afterCreating(function (Instituicao $instituicao) {
            // Adiciona um relacionamento com vários voluntários
            $voluntarios = Voluntario::where('id_voluntario', '!=', 1) // Exclui o voluntário com id 1
                            ->inRandomOrder()
                            ->take(rand(10, 50)) // Aumenta o número de voluntários por evento
                            ->pluck('id_voluntario')
                            ->unique();


            foreach ($voluntarios as $id_voluntario) {
                $createdAt = $this->faker->dateTimeBetween('-1 year', 'now');
                $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');

                $instituicao->voluntarios()->attach($id_voluntario, [
                    'situacao_solicitacao_voluntario' => $this->faker->randomElement([-1, 0, 1]),
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
                    'updated_at' => $updatedAt
                ]);
            }

            //'Doar Agora' das instituições
            Doacao::create([
                'id_instituicao' => $instituicao->id_instituicao,
                'observacao_doacao' => null,
                'data_hora_limite_doacao' => null,
                'nome_doacao' => null,
                'coleta_doacao' => null,
                'card_doacao' => '0'
            ]);
        });
    }
}
