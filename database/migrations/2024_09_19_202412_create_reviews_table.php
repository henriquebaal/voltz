<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');  // Ligação com o pedido que foi avaliado
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Ligação com o cliente que fez a avaliação
            $table->integer('rating')->default(0);  // Avaliação de 1 a 5 estrelas
            $table->text('comment')->nullable();  // Comentário do cliente
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
    
};
