<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('thumbnail');
            $table->integer('creator_id');
            $table->integer('starting_price')->default(5);
            $table->integer('current_price')->default(5)->nullable();
            $table->integer('category_id')->default(1);
            $table->string('category_type');
            $table->integer('isLimited')->default(0);
            $table->integer('stock')->nullable(); //default is null because most items won't be limiteds.
            //may need to add more later idk.
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
        Schema::dropIfExists('items');
    }
}
