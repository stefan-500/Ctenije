<?php

use App\Models\DiscountCode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('porudzbinas', function (Blueprint $table) {
            $table->integer('subtotal')->default(0)->after('adresa_isporuke');
            $table->foreignIdFor(DiscountCode::class)->nullable()->after('subtotal')->constrained()->nullOnDelete();
            $table->string('discount_code', 50)->nullable()->after('discount_code_id');
            $table->string('discount_type', 20)->nullable()->after('discount_code');
            $table->integer('discount_value')->nullable()->after('discount_type');
            $table->integer('discount_amount')->default(0)->after('discount_value');
        });
    }

    public function down(): void
    {
        Schema::table('porudzbinas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('discount_code_id');
            $table->dropColumn([
                'subtotal',
                'discount_code',
                'discount_type',
                'discount_value',
                'discount_amount',
            ]);
        });
    }
};
