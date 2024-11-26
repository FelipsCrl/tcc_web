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
        Schema::create('evento_has_habilidade', function (Blueprint $table) {
            $table->unsignedBigInteger('id_evento');
            $table->unsignedBigInteger('id_habilidade');
            $table->primary(['id_evento', 'id_habilidade']);
            $table->float('meta_evento');
            $table->float('quantidade_voluntario');
            $table->timestamps();

            $table->foreign('id_evento')->references('id_evento')->on('evento')->onDelete('cascade');;
            $table->foreign('id_habilidade')->references('id_habilidade')->on('habilidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_has_habilidade');
    }
};
