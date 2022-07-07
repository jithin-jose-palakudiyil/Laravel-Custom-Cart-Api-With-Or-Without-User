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
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');  
            $table->longText('products');
            $table->decimal('totalPrice');
            $table->string('name');
            $table->text('address');
            $table->integer('phone');
            $table->string('email'); 
            $table->tinyInteger('is_guest')->default(2)->comment('1-yes,2-no');
            $table->string('transactionID')->nullable(true);
            $table->integer('userID')->nullable(true); 
           

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
        Schema::dropIfExists('order');
    }
};
