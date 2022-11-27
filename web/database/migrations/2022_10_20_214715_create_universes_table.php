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
        Schema::create('universes', function (Blueprint $table) {
            $table->id();
			
			$table->unsignedBigInteger('creatorId');
			$table->string('name');
			
			$table->unsignedBigInteger('startPlaceId');
			
			$table->boolean('public')->default(false);
			$table->boolean('studioApiServices')->default(false);
			
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
        Schema::dropIfExists('universes');
    }
};
