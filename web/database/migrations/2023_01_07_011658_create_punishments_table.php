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
        Schema::create('punishments', function (Blueprint $table) {
            $table->id();
			
			$table->unsignedTinyInteger('punishment_type_id');
			$table->boolean('active');
			$table->string('user_note');
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('moderator_id');
			$table->unsignedBigInteger('pardoner_id')->nullable();
			$table->timestamp('expiration')->nullable();
			
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
        Schema::dropIfExists('punishments');
    }
};
