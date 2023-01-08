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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
			
			$table->unsignedSmallInteger('transaction_type_id');
			$table->unsignedBigInteger('asset_id')->nullable();
			$table->unsignedBigInteger('place_id')->nullable();
			$table->unsignedBigInteger('user_id');
			$table->bigInteger('delta');
			$table->unsignedBigInteger('seller_id');
			
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
        Schema::dropIfExists('transactions');
    }
};
