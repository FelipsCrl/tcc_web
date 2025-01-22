<?php

namespace Database\Seeders;

use App\Models\Contato;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Exemplo de contato 1
        Contato::create([
            'telefone_contato' => '(11) 98765-4321',
            'whatsapp_contato' => '(11) 91234-5678',
            'facebook_contato' => 'https://facebook.com/exemplo1',
            'instagram_contato' => 'https://instagram.com/exemplo1',
            'site_contato' => 'https://exemplo1.com'
        ]);

        // Exemplo de contato 2
        Contato::create([
            'telefone_contato' => '(21) 99876-5432',
            'whatsapp_contato' => '(21) 92345-6789',
        ]);

        //Contato::factory()->count(20)->create();
    }
}
