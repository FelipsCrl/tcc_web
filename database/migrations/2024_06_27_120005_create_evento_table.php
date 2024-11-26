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
        Schema::create('evento', function (Blueprint $table) {
            $table->id('id_evento');
            $table->string('descricao_evento', 150);
            $table->dateTime('data_hora_evento');
            $table->unsignedBigInteger('id_instituicao');
            $table->unsignedBigInteger('id_endereco')->nullable();
            $table->dateTime('data_hora_limite_evento');
            $table->string('nome_evento', 100);
            $table->timestamps();

            $table->foreign('id_instituicao')->references('id_instituicao')->on('instituicao');
            $table->foreign('id_endereco')->references('id_endereco')->on('endereco');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento');
    }
};
