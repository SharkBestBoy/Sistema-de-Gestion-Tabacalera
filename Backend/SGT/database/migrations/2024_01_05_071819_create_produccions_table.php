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
        Schema::create('produccions', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->integer('idVitola');
            $table->foreignId('idVitola')->references('id')->on('vitolas');
            $table->integer('idFecha');
            $table->foreignId('idFecha')->references('id')->on('fechas');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produccions');
    }
};
