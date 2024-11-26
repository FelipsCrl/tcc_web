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
        Schema::create('instituicao_has_voluntario', function (Blueprint $table) {
            $table->unsignedBigInteger('id_instituicao');
            $table->unsignedBigInteger('id_voluntario');
            $table->tinyInteger('situacao_solicitacao_voluntario');
            $table->string('habilidade_voluntario');
            $table->primary(['id_instituicao', 'id_voluntario']);
            $table->timestamps();

            $table->foreign('id_instituicao')->references('id_instituicao')->on('instituicao');
            $table->foreign('id_voluntario')->references('id_voluntario')->on('voluntario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instituicao_has_voluntario');
    }
};
