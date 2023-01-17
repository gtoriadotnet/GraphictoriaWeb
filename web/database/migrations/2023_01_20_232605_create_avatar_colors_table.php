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
        Schema::create('avatar_colors', function (Blueprint $table) {
            $table->id();
			
			$table->unsignedBigInteger('owner_id')->unique();
			$table->unsignedBigInteger('head');
			$table->unsignedBigInteger('torso');
			$table->unsignedBigInteger('leftArm');
			$table->unsignedBigInteger('rightArm');
			$table->unsignedBigInteger('leftLeg');
			$table->unsignedBigInteger('rightLeg');
			
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
        Schema::dropIfExists('avatar_colors');
    }
};
