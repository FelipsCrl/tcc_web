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
        Schema::create('contato', function (Blueprint $table) {
            $table->id('id_contato');
            $table->string('telefone_contato', 15)->unique();
            $table->string('whatsapp_contato', 15)->unique()->nullable();
            $table->string('facebook_contato', 60)->unique()->nullable();
            $table->string('instagram_contato', 60)->unique()->nullable();
            $table->string('site_contato', 60)->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contato');
    }
};
