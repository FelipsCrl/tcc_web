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
        Schema::create('voluntario_has_doacao', function (Blueprint $table) {
            $table->unsignedBigInteger('id_voluntario');
            $table->unsignedBigInteger('id_doacao');
            $table->tinyInteger('situacao_solicitacao_doacao');
            $table->dateTime('data_hora_coleta')->nullable();
            $table->string('categoria_doacao', 45);
            $table->float('quantidade_doacao');
            $table->primary(['id_voluntario', 'id_doacao']);
            $table->timestamps();

            $table->foreign('id_voluntario')->references('id_voluntario')->on('voluntario');
            $table->foreign('id_doacao')->references('id_doacao')->on('doacao')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voluntario_has_doacao');
    }
};
