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
            // $table->id();
            $table->string('isbn', 13)->primary();
            $table->integer('izdanje');
            $table->integer('br_stranica');
            $table->enum('pismo', ['Ćirilica', 'Latinica'])->default('Ćirilica');
            $table->timestamps();
            $table->foreignIdFor(Artikal::class)->constrained()->onDelete('cascade');
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
