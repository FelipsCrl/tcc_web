<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Exemplo de usuário 1
        User::create([
            'name' => 'Associação Ajuda',
            'email' => 'associacao@gmail.com',
            'password' => Hash::make('teste123'),
        ]);

        // Exemplo de usuário 2
        User::create([
            'name' => 'Felipe Leal',
            'email' => 'felipe@gmail.com',
            'password' => Hash::make('teste123'),
        ]);

        User::factory()->count(20)->create();
    }
}
