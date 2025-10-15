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
        Schema::create('bekanntmachung_cpv', function (Blueprint $table) {
            $table->foreignId('bekanntmachung_id')->constrained('bekanntmachungen')->onDelete('cascade');
            $table->string('cpv_code');
            $table->foreign('cpv_code')->references('code')->on('cpv_codes')->onDelete('cascade');

            $table->primary(['bekanntmachung_id', 'cpv_code']);
            $table->index('cpv_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bekanntmachung_cpv');
    }
};
