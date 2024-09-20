<?php

use App\Models\Artikal;
use App\Models\VrstaArtikala;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vrsta_artikalas', function (Blueprint $table) {
            $table->id();
            $table->string('naziv', 50);
            // constrained('vrsta_artikalas') - Bez argumenta sa nazivom tabele Laravel bi pretpostavio da se tabela kljuca 'nadvrsta_id' zove nadvrstas
            $table->foreignIdFor(VrstaArtikala::class, 'nadvrsta_id')->nullable()->constrained('vrsta_artikalas')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('artikal_vrsta_artikala', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Artikal::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(VrstaArtikala::class)->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikal_vrsta_artikala');
        Schema::dropIfExists('vrsta_artikalas');
    }
};
