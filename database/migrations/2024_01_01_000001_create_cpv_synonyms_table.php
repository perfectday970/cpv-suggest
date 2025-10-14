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
        Schema::create('cpv_synonyms', function (Blueprint $table) {
            $table->id();
            $table->string('term');
            $table->string('code', 9);
            $table->float('weight')->default(1.0);
            $table->timestamps();

            $table->index('term');
            $table->index('code');
            $table->foreign('code')->references('code')->on('cpv_codes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpv_synonyms');
    }
};
