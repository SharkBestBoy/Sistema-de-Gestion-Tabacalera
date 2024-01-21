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
            $table->integer('cant_producida');
            $table->integer('cant_trabajadores');
            $table->foreignId('vitola_id')
            ->references('id')
            ->on('vitolas')
            ->nullable()
            ->onDelete('SET NULL');
            $table->foreignId('brigada_id')
            ->references('id')
            ->on('brigadas')
            ->nullable()
            ->onDelete('SET NULL');
            $table->foreignId('fecha_id')
            ->references('id')
            ->on('fechas')
            ->nullable()
            ->onDelete('SET NULL');
            
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
