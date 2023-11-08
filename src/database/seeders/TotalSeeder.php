<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\CarbonPeriod;

class TotalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(10)->create()->each(function ($user) {
        $period = CarbonPeriod::create('2023-11-06', '2023-12-06');
        
        foreach ($period as $date) {
            $attendance = \App\Models\Attendance::factory()->create([
                'user_id' => $user->id,
                'date' => $date->format('Y-m-d'),
                'work_start' => $date->format('Y-m-d 09:00:00'),
                'work_end' => $date->format('Y-m-d 17:00:00'),
            ]);

            $attendance->breakTimes()->saveMany(\App\Models\BreakTime::factory()->count(1)->make([
                'break_start' => $date->format('Y-m-d 12:00:00'),
                'break_end' => $date->format('Y-m-d 13:00:00'),
            ]));
        }
    });
    }
}

