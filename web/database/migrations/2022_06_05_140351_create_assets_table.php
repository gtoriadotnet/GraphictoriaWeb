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
			
			$table->string('thumbnail2DHash')->nullable();
			$table->string('thumbnail3DHash')->nullable();
			
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
