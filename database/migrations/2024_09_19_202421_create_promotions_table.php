<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('promo_code')->unique();  // Código promocional
            $table->decimal('discount_value', 8, 2);  // Valor de desconto
            $table->timestamp('start_date');  // Data de início da promoção
            $table->timestamp('end_date')->nullable();  // Data de término da promoção
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
    
};
