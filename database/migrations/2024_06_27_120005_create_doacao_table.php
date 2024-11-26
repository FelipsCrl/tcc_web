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
        Schema::create('doacao', function (Blueprint $table) {
            $table->id('id_doacao');
            $table->unsignedBigInteger('id_instituicao');
            $table->string('observacao_doacao', 150)->nullable();
            $table->dateTime('data_hora_limite_doacao')->nullable();
            $table->string('nome_doacao', 100)->nullable();
            $table->tinyInteger('coleta_doacao')->nullable();
            $table->tinyInteger('card_doacao');
            $table->timestamps();

            $table->foreign('id_instituicao')->references('id_instituicao')->on('instituicao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doacao');
    }
};
