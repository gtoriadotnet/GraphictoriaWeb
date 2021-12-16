<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFflagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fflags', function (Blueprint $table) {
            $table->id();
			$table->string('name');
			$table->string('value');
			$table->enum('dataType', ['Log', 'Int', 'String', 'Boolean']);
			$table->enum('type', ['Unscoped', 'Fast', 'Dynamic', 'Synchronised']);
			$table->bigInteger('groupId');
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
        Schema::dropIfExists('fflags');
    }
}
