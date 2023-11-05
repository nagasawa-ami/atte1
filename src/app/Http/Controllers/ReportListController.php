<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\User;
use Carbon\Carbon;

class ReportListController extends Controller
{
   public function index(Request $request)
{
    $date = $request->input('date', Carbon::today()->toDateString());


    $carbonDate = Carbon::parse($date);
    
    $prevDate = $carbonDate->copy()->subDay()->toDateString();
    $nextDate = $carbonDate->copy()->addDay()->toDateString();




    $attendances = Attendance::with(['user', 'breakTimes'])
                             ->whereDate('date', $date)
                             ->paginate(5);

    foreach ($attendances as $attendance) {
        // 休憩時間の合計を計算
        $totalBreakMinutes = $attendance->breakTimes->sum(function ($breakTime) {
            return Carbon::parse($breakTime->break_end)->diffInMinutes(Carbon::parse($breakTime->break_start));
        });

        // 勤務時間の合計を計算（休憩時間を除く）
        $workStart = Carbon::parse($attendance->work_start);
        $workEnd = $attendance->work_end ? Carbon::parse($attendance->work_end) : Carbon::now();
        $totalWorkMinutes = $workStart->diffInMinutes($workEnd) - $totalBreakMinutes;

        // 時間のフォーマット（HH:MM:SS）
        $attendance->totalBreakTime = sprintf('%02d:%02d:%02d', ($totalBreakMinutes / 60), ($totalBreakMinutes % 60), 0);
        $attendance->totalWorkTime = sprintf('%02d:%02d:%02d', ($totalWorkMinutes / 60), ($totalWorkMinutes % 60), 0);
    }

    return view('attendance', compact('attendances', 'date', 'prevDate', 'nextDate'));
}

}

