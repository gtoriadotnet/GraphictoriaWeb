<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	use HasFactory;
	
	/**
	 * The database connection that should be used by the migration.
	 *
	 * @var string
	 */
	protected $connection = 'mysql-membership';

	protected $hidden = [
        'password',
        'remember_token',
        'email',
        'email_verified_at',
    ];

}
