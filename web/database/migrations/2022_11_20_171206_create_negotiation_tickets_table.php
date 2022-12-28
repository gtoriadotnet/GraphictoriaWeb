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
        Schema::create('negotiation_tickets', function (Blueprint $table) {
            $table->id();
			$table->string('ticket');
			$table->unsignedBigInteger('userId');
			$table->boolean('used')->default(false);
			$table->boolean('maintenanceBypass')->default(false);
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
        Schema::dropIfExists('negotiation_tickets');
    }
};
