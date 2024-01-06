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
        Schema::create('brigada_produccion', function (Blueprint $table) {
            $table->id();
            $table->integer('idBrigada');
            $table->foreignId('idBrigada')->references('id')->on('brigadas');
            $table->integer('idProduccion');
            $table->foreignId('idProduccion')->references('id')->on('produccions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brigada_produccion');
    }
};
