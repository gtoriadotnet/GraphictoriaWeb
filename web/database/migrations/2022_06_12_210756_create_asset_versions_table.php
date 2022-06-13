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
        Schema::create('asset_versions', function (Blueprint $table) {
            $table->id();
			
			$table->unsignedBigInteger('parentAsset');
			$table->unsignedBigInteger('localVersion');
			
			// Calculating the subdomain on runtime is too expensive.
			// So full URLs are used instead of just the hashes.
			$table->string('contentURL');
			
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
        Schema::dropIfExists('asset_versions');
    }
};
