<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;


    public function setUp(): void
    {
    parent::setUp();
    Carbon::setTestNow(Carbon::now());
    }


    public function testUserCanStartWork()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/start-work');

        $response->assertRedirect('/attendance');
        $response->assertSessionHas('message', '勤務を開始しました。');
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'work_start' => Carbon::now()->toDateTimeString(),
        ]);
    }

    public function testUserCanEndWork()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_start' => Carbon::now()->subHours(9)
        ]);

        $response = $this->actingAs($user)->post('/end-work');

        $response->assertRedirect('/attendance');
        $response->assertSessionHas('message', '勤務を終了しました。');
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'work_end' => Carbon::now()->toDateTimeString(),
        ]);
    }


    public function testUserCanStartBreak()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_start' => Carbon::now()->subHours(4)
        ]);

        $response = $this->actingAs($user)->post('/start-break');

        $response->assertRedirect('/attendance');
        $response->assertSessionHas('message', '休憩を開始しました。');
        $this->assertDatabaseHas('break_times', [
            'attendance_id' => $attendance->id,
            'break_start' => Carbon::now()->toDateTimeString(),
        ]);
    }

    public function testUserCanEndBreak()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_start' => Carbon::now()->subHours(4)
        ]);
        $break = BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
            'break_start' => Carbon::now()->subHour()
        ]);

        $response = $this->actingAs($user)->post('/end-break');

        $response->assertRedirect('/attendance.index');
        $response->assertSessionHas('message', '休憩を終了しました。');
        $this->assertDatabaseHas('break_times', [
            'attendance_id' => $attendance->id,
            'break_end' => Carbon::now()->toDateTimeString(),
        ]);
    }
}