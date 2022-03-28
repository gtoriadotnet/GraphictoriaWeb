<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	/**
	 * The database connection that should be used by the migration.
	 *
	 * @var string
	 */
	protected $connection = 'mysql-membership';
	
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
			$table->string('username');
			$table->string('email');
            $table->timestamp('email_verified_at')->nullable();
			$table->string('password');
            $table->integer('bank')->default(15);
            $table->string('about')->nullable();
            $table->json('rolesets')->default('[1]');
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
        Schema::dropIfExists('users');
    }
}
