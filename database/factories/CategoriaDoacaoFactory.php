<?php

namespace Database\Factories;

use App\Models\CategoriaDoacao;
use App\Models\Doacao;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategoriaDoacao>
 */
class CategoriaDoacaoFactory extends Factory
{
    protected $model = CategoriaDoacao::class;

    public function definition(): array
    {
        return [
            'descricao_categoria' => $this->faker->sentence(2),
            /*
            'descricao_categoria' => $this->faker->randomElement([
                'Alimentos não perecíveis',
                'Roupas e acessórios',
                'Material escolar',
                'Produtos de higiene pessoal',
                'Brinquedos'
            ]),
            */
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (CategoriaDoacao $categoriaDoacao) {
            // Buscar algumas doações existentes e associar
            $doacoesExistentes = Doacao::inRandomOrder()->take(10)->get(); // Pegue 10 doações existentes aleatoriamente

            foreach ($doacoesExistentes as $doacao) {
                $categoriaDoacao->doacoes()->attach($doacao->id_doacao, [
                    'meta_doacao_categoria' => $this->faker->numberBetween(10, 100),
                    'quantidade_doacao_categoria' => $this->faker->numberBetween(1, 50),
                ]);
            }
        });
    }
}
