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
        Schema::create('voluntario_has_evento', function (Blueprint $table) {
            $table->unsignedBigInteger('id_voluntario');
            $table->unsignedBigInteger('id_evento');
            $table->string('habilidade_voluntario', 45);
            $table->primary(['id_voluntario', 'id_evento']);
            $table->timestamps();

            $table->foreign('id_voluntario')->references('id_voluntario')->on('voluntario');
            $table->foreign('id_evento')->references('id_evento')->on('evento')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voluntario_has_evento');
    }
};
