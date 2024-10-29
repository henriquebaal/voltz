<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');  // Nome do item no estoque (ex: Pão, Carne, Molho)
            $table->integer('quantity');  // Quantidade disponível em estoque
            $table->integer('minimum_quantity')->default(20);  // Quantidade mínima antes de alerta de baixo estoque
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
    
};
