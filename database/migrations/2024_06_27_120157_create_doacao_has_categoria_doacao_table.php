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
        Schema::create('doacao_has_categoria_doacao', function (Blueprint $table) {
            $table->unsignedBigInteger('id_doacao');
            $table->unsignedBigInteger('id_categoria');
            $table->primary(['id_doacao', 'id_categoria']);
            $table->float('meta_doacao_categoria')->nullable();
            $table->float('quantidade_doacao_categoria');
            $table->timestamps();

            $table->foreign('id_doacao')->references('id_doacao')->on('doacao')->onDelete('cascade');
            $table->foreign('id_categoria')->references('id_categoria')->on('categoria_doacao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doacao_has_categoria_doacao');
    }
};
