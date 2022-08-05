<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Roleset;

class RolesetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Roleset::create(['name' => 'Owner']);
        Roleset::create(['name' => 'Administrator']);
        Roleset::create(['name' => 'Moderator']);
        Roleset::create(['name' => 'ProtectedUser']);
        Roleset::create(['name' => 'BetaTester']);
        Roleset::create(['name' => 'QATester']);
        Roleset::create(['name' => 'Soothsayer']);
    }
}
