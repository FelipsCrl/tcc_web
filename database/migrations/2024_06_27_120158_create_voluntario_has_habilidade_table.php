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
        Schema::create('voluntario_has_habilidade', function (Blueprint $table) {
            $table->unsignedBigInteger('id_voluntario');
            $table->unsignedBigInteger('id_habilidade');
            $table->primary(['id_voluntario', 'id_habilidade']);
            $table->timestamps();

            $table->foreign('id_voluntario')->references('id_voluntario')->on('voluntario');
            $table->foreign('id_habilidade')->references('id_habilidade')->on('habilidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habilidade_has_voluntario');
    }
};
