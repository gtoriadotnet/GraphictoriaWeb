<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\PunishmentType;

class PunishmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PunishmentType::create(['name' => 'None', 'label' => 'Unban', 'time' => 0]);
        PunishmentType::create(['name' => 'Remind', 'label' => 'Reminder', 'time' => 0]);
        PunishmentType::create(['name' => 'Warn', 'label' => 'Warning', 'time' => 0]);
        PunishmentType::create(['name' => 'Ban 1 Day', 'label' => 'Banned for 1 Day', 'time' => 1]);
        PunishmentType::create(['name' => 'Ban 3 Days', 'label' => 'Banned for 3 Days', 'time' => 3]);
        PunishmentType::create(['name' => 'Ban 7 Days', 'label' => 'Banned for 7 Days', 'time' => 7]);
        PunishmentType::create(['name' => 'Ban 14 Days', 'label' => 'Banned for 14 Days', 'time' => 14]);
        PunishmentType::create(['name' => 'Delete', 'label' => 'Account Deleted']);
    }
}
