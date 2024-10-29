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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do produto
            $table->text('description')->nullable(); // Descrição do produto
            $table->decimal('price', 8, 2); // Preço do produto
            $table->string('image')->nullable(); // Caminho da imagem do produto
            $table->timestamps(); // Created_at e Updated_at
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
