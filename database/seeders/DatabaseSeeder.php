<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            EnderecoSeeder::class,
            ContatoSeeder::class,
            HabilidadeSeeder::class,
            VoluntarioSeeder::class,
            InstituicaoSeeder::class,
            EventoSeeder::class,
            DoacaoSeeder::class,
            CategoriaDoacaoSeeder::class,
        ]);
    }
}
