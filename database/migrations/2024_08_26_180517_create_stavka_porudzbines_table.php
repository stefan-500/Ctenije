<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Artikal;
use App\Models\Porudzbina;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stavka_porudzbines', function (Blueprint $table) {
            $table->id();
            $table->integer('kolicina');
            $table->integer('ukupna_cijena');
            $table->foreignIdFor(Artikal::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Porudzbina::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stavka_porudzbines');
    }
};
