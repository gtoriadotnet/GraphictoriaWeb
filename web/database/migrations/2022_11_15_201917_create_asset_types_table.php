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
        Schema::create('asset_types', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->nullable();
			$table->string('name')->nullable();
			$table->boolean('wearable')->default(false);
			$table->boolean('renderable')->default(false);
			$table->boolean('renderable3d')->default(false);
			$table->boolean('copyable')->default(false); // Can be downloaded through /asset
			$table->boolean('sellable')->default(false); // If false, can be made on sale for free only.
			$table->boolean('locked')->default(false); // Cannot be put on sale
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
        Schema::dropIfExists('asset_types');
    }
};
