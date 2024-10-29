<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Código do cupom
            $table->decimal('discount', 5, 2); // Valor do desconto
            $table->dateTime('valid_from')->nullable(); // Data de início da validade
            $table->dateTime('valid_until')->nullable(); // Data de fim da validade
            $table->timestamps();
        });
    }
    
};
