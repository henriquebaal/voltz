<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeOrderIdNullableInLoyaltyPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loyalty_points', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loyalty_points', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable(false)->change();
        });
    }
}
