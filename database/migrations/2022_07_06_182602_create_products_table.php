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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');  
            $table->string('label')->nullable(false);
            $table->string('type')->nullable(false);
            $table->text('DownloadURL')->nullable(true);
            $table->integer('Weight')->nullable(true);
            $table->decimal('price');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('category_id')->nullable()->unsigned(); 
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->softDeletesTz(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
