<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\GuestDeliveryData;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('porudzbinas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('datum');
            $table->string('adresa_isporuke', 80);
            $table->integer('ukupno');
            $table->enum('status', ['neobradjeno', 'u obradi', 'zakljuceno', 'odbijeno']);
            $table->string('stripe_payment_intent_id')->nullable();
            $table->foreignIdFor(User::class)->nullable()->constrained()->onDelete('set null');
            $table->string('payment_token', 64)->nullable()->unique();
            $table->foreignIdFor(GuestDeliveryData::class)->nullable()->constrained()->onDelete('cascade');
            $table->boolean('email_poslat')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('porudzbinas');
    }
};
