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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
			
			$table->unsignedBigInteger('creatorId');
			$table->string('name');
			$table->string('description')->nullable();
			
			$table->boolean('approved')->default(false);
			$table->boolean('moderated')->default(false);
			
			$table->unsignedBigInteger('priceInTokens')->default(15);
			$table->unsignedBigInteger('sales')->default(0);
			$table->boolean('onSale')->default(false);
			
			$table->unsignedSmallInteger('assetTypeId');
			$table->unsignedSmallInteger('assetAttributeId')->nullable();
			$table->unsignedBigInteger('assetVersionId')->comment('The most recent version id for the asset. This is used internally as asset version 0 when using the /asset api.');
			
			// Calculating the subdomain on runtime is too expensive.
			// So full URLs are used instead of just the hashes.
			$table->string('thumbnailURL')->nullable();
			$table->string('3dThumbnailURL')->nullable();
			
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
};
