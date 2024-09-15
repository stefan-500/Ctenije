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
        Schema::create('guest_delivery_datas', function (Blueprint $table) {
            $table->id();
            $table->string('ime', 15);
            $table->string('prezime', 20);
            $table->string('email', 50);
            $table->string('adresa', 80);
            $table->string('tel', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_delivery_datas');
    }
};
