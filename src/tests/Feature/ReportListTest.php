<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class ReportListTest extends TestCase
{
    use RefreshDatabase; // テスト後にデータベースをリセットする

    public function test_attendance_report_list_for_a_date()
    {
        // テストユーザーと勤怠情報を作成
        $user = User::factory()->create();
        $date = Carbon::today()->toDateString();
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => $date,
            'work_start' => Carbon::parse($date . ' 08:00:00'),
            'work_end' => Carbon::parse($date . ' 17:00:00'),
        ]);
        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => Carbon::parse($date . ' 12:00:00'),
            'break_end' => Carbon::parse($date . ' 13:00:00'),
        ]);

        // ユーザーとして認証
        $this->actingAs($user);

        // 日付指定でレポートリストページにアクセス
        $response = $this->get('/report-list?date=' . $date);

        // レスポンスの検証
        $response->assertStatus(200)
                 ->assertViewHas('attendances', function ($viewAttendances) use ($user, $attendance) {
                     // ページネーションが含まれているか検証
                     return $viewAttendances->first()->user_id === $user->id
                            && $viewAttendances->first()->id === $attendance->id;
                 });

        // 勤務時間と休憩時間の合計が正しいか検証
        $response->assertSee('08:00:00', false)
                 ->assertSee('17:00:00', false)
                 ->assertSee('01:00:00', false); // 休憩時間
    }
}