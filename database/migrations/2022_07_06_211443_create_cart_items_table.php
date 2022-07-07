<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('id');  
            $table->unsignedInteger('quantity');
            $table->foreign('cart_id')->references('id')->on('cart')->onDelete('cascade');
            $table->integer('cart_id')->nullable()->unsigned();  
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('product_id')->nullable()->unsigned(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
};
