<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
	/**
	 * The database connection that should be used by the migration.
	 *
	 * @var string
	 */
	protected $connection = 'mysql-asset';
	
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// TODO: relational genres?
		// god i fucking hate foreign keys
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('creatorId');
			$table->longText('title');
			$table->longText('description');
			$table->json('settings');
			$table->boolean('commentsEnabled')->default(true);
			$table->boolean('purchasable')->default(false);
			$table->unsignedBigInteger('assetVersionId');
			$table->unsignedBigInteger('assetTypeId');
			$table->unsignedBigInteger('assetGenreId');
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
        Schema::dropIfExists('assets');
    }
}
