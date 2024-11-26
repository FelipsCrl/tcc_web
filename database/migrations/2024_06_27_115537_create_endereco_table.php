<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('endereco', function (Blueprint $table) {
            $table->id('id_endereco');
            $table->string('cep_endereco', 9);
            $table->string('complemento_endereco', 50);
            $table->string('cidade_endereco', 50);
            $table->string('logradouro_endereco', 50);
            $table->string('estado_endereco', 2);
            $table->string('bairro_endereco', 50);
            $table->integer('numero_endereco');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('endereco');
    }
};
