<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
			$table->string('name');
			$table->string('description')->nullable();
			$table->unsignedInteger('creator');
			$table->enum('status', ['unmoderated', 'review', 'deleted'])->default('unmoderated');
			$table->unsignedInteger('genre')->default(0); // bitwise flags
			$table->unsignedInteger('allowed_gears')->default(0); // bitwise flags
			$table->unsignedInteger('players_ingame')->default(0);
			$table->unsignedInteger('visits')->default(0);
			$table->unsignedInteger('max_players')->default(0);
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
        Schema::dropIfExists('games');
    }
}
