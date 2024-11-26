<?php

namespace Database\Factories;

use App\Models\Contato;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contato>
 */
class ContatoFactory extends Factory
{
    protected $model = Contato::class;

    public function definition(): array
    {
        $maxLength = 60; // Define o comprimento máximo da URL
        $baseUrl = 'https://example.com/';
        $pathLength = $maxLength - strlen($baseUrl); // Calcula o comprimento permitido para a parte do caminho

        return [
            'telefone_contato' => $this->faker->numerify('(##) #####-####'), // Gerar número de telefone no formato (XX) XXXXX-XXXX
            'whatsapp_contato' => $this->faker->numerify('(##) #####-####'), // Gerar número de WhatsApp no formato (XX) XXXXX-XXXX
            'facebook_contato' => $baseUrl . $this->faker->lexify(str_repeat('?', $pathLength)), // Gera URL do Facebook
            'instagram_contato' => $baseUrl . $this->faker->lexify(str_repeat('?', $pathLength)), // Gera URL do Instagram
            'site_contato' => $baseUrl . $this->faker->lexify(str_repeat('?', $pathLength)), // Gera URL do site
        ];
    }
}
