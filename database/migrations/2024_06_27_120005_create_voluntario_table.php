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
        Schema::create('voluntario', function (Blueprint $table) {
            $table->id('id_voluntario');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_contato')->nullable();
            $table->unsignedBigInteger('id_endereco')->nullable();
            $table->string('cpf_voluntario',14)->unique();
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
        Schema::dropIfExists('voluntario');
    }
};
