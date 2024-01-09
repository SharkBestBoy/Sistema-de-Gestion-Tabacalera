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
            $table->foreignId('brigada_id')
            ->references('id')
            ->on('brigadas')
            ->nullable()
            ->onDelete('SET NULL');
            $table->foreignId('produccion_id')
            ->references('id')
            ->on('produccions')
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
        Schema::dropIfExists('brigada_produccion');
    }
};
