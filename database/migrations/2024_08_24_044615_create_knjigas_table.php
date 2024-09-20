<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Artikal;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('knjigas', function (Blueprint $table) {
            $table->string('isbn', 13)->primary();
            $table->string('autor', 40);
            $table->string('izdavac', 200);
            $table->integer('izdanje');
            $table->integer('br_stranica');
            $table->enum('pismo', ['Ćirilica', 'Latinica'])->default('Ćirilica');
            $table->timestamps();
            $table->foreignIdFor(Artikal::class)->constrained()->onDelete('cascade');
            $table->softDeletes(); // Dodaje 'deleted_at' kolonu
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knjigas');
    }
};
