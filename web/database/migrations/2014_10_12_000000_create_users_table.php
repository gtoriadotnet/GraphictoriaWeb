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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
			
            $table->string('biography')->nullable();
			
			$table->unsignedBigInteger('tokens')->default(0);
			$table->dateTime('next_reward')->useCurrent();
			
			$table->string('thumbnailBustHash')->nullable();
			$table->string('thumbnail2DHash')->nullable();
			$table->string('thumbnail3DHash')->nullable();
			
			$table->dateTime('last_seen')->useCurrent();
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
        Schema::dropIfExists('users');
    }
};
