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
        Schema::table('cpv_codes', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['parent_code']);

            // Drop the parent_code column and its index
            $table->dropIndex(['parent_code']);
            $table->dropColumn('parent_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cpv_codes', function (Blueprint $table) {
            // Re-add the parent_code column
            $table->string('parent_code', 9)->nullable()->after('level');
            $table->index('parent_code');

            // Re-add foreign key constraint
            $table->foreign('parent_code')
                  ->references('code')
                  ->on('cpv_codes')
                  ->onDelete('cascade');
        });
    }
};
