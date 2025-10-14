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
        // Create the table first without foreign key
        Schema::create('cpv_codes', function (Blueprint $table) {
            $table->string('code', 9)->primary();
            $table->text('title');
            $table->integer('level');
            $table->string('parent_code', 9)->nullable();
            $table->timestamps();

            $table->index('level');
            $table->index('parent_code');
        });

        // Add foreign key constraint after table is created
        Schema::table('cpv_codes', function (Blueprint $table) {
            $table->foreign('parent_code')
                  ->references('code')
                  ->on('cpv_codes')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpv_codes');
    }
};
