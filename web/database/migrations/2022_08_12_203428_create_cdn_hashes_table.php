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
        Schema::create('cdn_hashes', function (Blueprint $table) {
            $table->id();
			$table->string('hash');
			$table->string('mime_type');
			$table->unsignedBigInteger('user_id')->nullable();
			$table->unsignedBigInteger('asset_version_id')->nullable();
			$table->boolean('deleted')->default(false); // XlXi: in the case of copyright or whatever
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
        Schema::dropIfExists('cdn_hashes');
    }
};
