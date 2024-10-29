<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');  // Ligação com a tabela de pedidos
            $table->string('item_name');  // Nome do item (ex: Hambúrguer X)
            $table->integer('quantity');  // Quantidade do item
            $table->decimal('price', 8, 2);  // Preço do item
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
