<?php

use App\Models\DiscountCode;
use App\Models\Porudzbina;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discount_code_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DiscountCode::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Porudzbina::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->string('email', 255);
            $table->integer('discount_amount');
            $table->timestamps();

            $table->unique('porudzbina_id');
            $table->index('email');
            $table->index(['discount_code_id', 'email']);
            $table->index(['discount_code_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_code_redemptions');
    }
};
