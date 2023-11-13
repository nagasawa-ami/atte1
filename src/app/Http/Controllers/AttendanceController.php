<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;
use Auth;




class AttendanceController extends Controller
{
    public function index()
{
    $this->checkAndSwitchToNextDay();

    $date = Carbon::today();
    $attendance = Attendance::where('user_id', Auth::id())->whereDate('date', $date)->first();
    $ongoingBreak = null;
    $isOnBreak = false;

    if ($attendance) {
        $ongoingBreak = BreakTime::where('attendance_id', $attendance->id)->whereNull('break_end')->first();
        if ($ongoingBreak) {
            $isOnBreak = true;
        }
    }

    return view('index', compact('attendance', 'ongoingBreak', 'isOnBreak'));
}

    public function startWork()
    {
        $this->checkAndSwitchToNextDay();

        $date = Carbon::today();
        $attendance = new Attendance([
            'user_id' => Auth::id(),
            'date' => $date,
            'work_start' => Carbon::now(),
        ]);
        $attendance->save();

        return redirect()->route('attendance.index')->with('message', '勤務を開始しました。');
    }

    public function endWork()
    {
        $attendance = Attendance::where('user_id', Auth::id())->whereDate('date', Carbon::today())->firstOrFail();
    $ongoingBreak = BreakTime::where('attendance_id', $attendance->id)->whereNull('break_end')->first();



    $attendance->work_end = Carbon::now();
    $attendance->save();

    return redirect()->route('attendance.index')->with('message', '勤務を終了しました。');
    }




    public function startBreak()
    {
        $attendance = Attendance::where('user_id', Auth::id())->whereDate('date', Carbon::today())->firstOrFail();
        $break = new BreakTime([
            'attendance_id' => $attendance->id,
            'break_start' => Carbon::now(),
        ]);
        $break->save();

        return redirect()->route('attendance.index')->with('message', '休憩を開始しました。');
    }

    public function endBreak()
    {
        $attendance = Attendance::where('user_id', Auth::id())->whereDate('date', Carbon::today())->firstOrFail();
        $break = BreakTime::where('attendance_id', $attendance->id)->whereNull('break_end')->firstOrFail();
        $break->break_end = Carbon::now();
        $break->save();

        return redirect()->route('attendance.index')->with('message', '休憩を終了しました。');
    }

    private function checkAndSwitchToNextDay()
    {
        $attendance = Attendance::where('user_id', Auth::id())->whereDate('date', '<', Carbon::today())->whereNull('work_end')->first();

        if ($attendance) {
            // 勤務が終了していない場合、勤務終了時間を設定し、新しい勤務レコードを作成する
            $attendance->work_end = Carbon::now();
            $attendance->save();

            $newAttendance = new Attendance([
                'user_id' => Auth::id(),
                'date' => Carbon::today(),
                'work_start' => Carbon::now(),
            ]);
            $newAttendance->save();
        }
    }
}