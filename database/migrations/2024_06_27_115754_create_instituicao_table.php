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
        Schema::create('instituicao', function (Blueprint $table) {
            $table->id('id_instituicao');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_contato');
            $table->unsignedBigInteger('id_endereco');
            $table->string('descricao_instituicao', 200)->nullable();
            $table->json('funcionamento_instituicao')->nullable();
            $table->string('cnpj_instituicao', 18)->unique();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('users');
            $table->foreign('id_contato')->references('id_contato')->on('contato');
            $table->foreign('id_endereco')->references('id_endereco')->on('endereco');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instituicao');
    }
};
