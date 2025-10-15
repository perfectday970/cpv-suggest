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
        Schema::create('bekanntmachungen', function (Blueprint $table) {
            $table->id();
            $table->date('veroeffentlicht')->comment('Veröffentlichungsdatum');
            $table->date('angebotsfrist')->nullable()->comment('Angebots- / Teilnahmefrist');
            $table->string('kurzbezeichnung')->comment('Kurzbezeichnung der Bekanntmachung');
            $table->string('typ')->comment('Typ der Bekanntmachung');
            $table->string('vergabeplattform')->comment('Vergabeplattform / Veröffentlicher');
            $table->decimal('geschaetzter_auftragswert', 15, 2)->nullable()->comment('Geschätzter Auftragswert in EUR');
            $table->text('beschreibung')->nullable()->comment('Detaillierte Beschreibung');
            $table->timestamps();

            $table->index('veroeffentlicht');
            $table->index('angebotsfrist');
            $table->index('typ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bekanntmachungen');
    }
};
