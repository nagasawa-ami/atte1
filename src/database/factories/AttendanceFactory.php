<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    public function definition()
    {
        $startHour = $this->faker->numberBetween(8, 10); // 勤務開始時間は8時から10時の間
        $workStart = Carbon::today()->setHour($startHour)->setMinute(0);

        return [
            // 'user_id' はシーダーで生成するのでここでは省略
            'date' => $workStart->toDateString(),
            'work_start' => $workStart->toDateTimeString(),
            'work_end' => $workStart->copy()->addHours(8)->toDateTimeString(), // 8時間後
            // 休憩時間は動的にシーダーで生成するのでここでは省略
        ];
    }
}

