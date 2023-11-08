<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class BreakTimeFactory extends Factory
{
    public function definition()
    {
        // 休憩開始時間を勤務開始時間の数時間後にランダムに設定
        $breakStartHour = $this->faker->numberBetween(12, 14); // 例えば、12時から14時の間でランダム
        $breakStart = Carbon::today()->setHour($breakStartHour)->setMinute($this->faker->numberBetween(0, 59));

        // 休憩終了時間を休憩開始時間の30分後に設定
        $breakEnd = $breakStart->copy()->addMinutes(30);

        return [
            // 'attendance_id' はシーダーで生成するのでここでは省略
            'break_start' => $breakStart->toDateTimeString(),
            'break_end' => $breakEnd->toDateTimeString(),
        ];
    }
}

