<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFflagsTable extends Migration
{
	/**
	 * The database connection that should be used by the migration.
	 *
	 * @var string
	 */
	protected $connection = 'mysql-fflag';
	
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
			$table->longText('value');
			$table->enum('dataType', ['Log', 'Int', 'String', 'Boolean']);
			$table->enum('type', ['Unscoped', 'Fast', 'Dynamic', 'Synchronised']);
			$table->bigInteger('bucketId');
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
