<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('artikals', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('naziv', 50);
            $table->text('opis');
            $table->integer('cijena');
            $table->integer('akcijska_cijena')->nullable();
            $table->integer('dostupna_kolicina');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikals');
    }
};
