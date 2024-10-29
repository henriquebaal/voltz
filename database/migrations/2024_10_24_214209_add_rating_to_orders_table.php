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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating')->nullable()->after('status'); // Adiciona a coluna rating
        });
    }
    
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('rating'); // Remove a coluna rating caso a migração seja revertida
        });
    }
    
};
