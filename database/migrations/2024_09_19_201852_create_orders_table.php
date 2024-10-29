<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Relaciona com a tabela de usuários
            $table->string('order_number');  // Número do pedido
            $table->string('status')->default('pendente');  // Status do pedido
            $table->string('payment_method')->nullable();  // Método de pagamento
            $table->string('delivery_address')->nullable();  // Endereço de entrega
            $table->decimal('total', 8, 2);  // Total do pedido
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
