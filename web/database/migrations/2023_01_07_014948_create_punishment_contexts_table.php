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
        Schema::create('punishment_contexts', function (Blueprint $table) {
            $table->id();
			
			$table->unsignedBigInteger('punishment_id');
			$table->string('user_note');
			$table->longText('description')->nullable();
			$table->string('content_hash')->nullable()->comment('Will display an image from the CDN.');
			
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
        Schema::dropIfExists('punishment_contexts');
    }
};
