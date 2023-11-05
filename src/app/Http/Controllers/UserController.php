<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;


class UserController extends Controller
{
    // public function index()
    // {
    //     return view('index');
    // }

    public function index()
{
    $userId = Auth::id();
    $today = now()->toDateString();

    $attendance = Attendance::where('user_id', $userId)->where('date', $today)->first();
    $ongoingBreak = null;

    if ($attendance) {
        $ongoingBreak = BreakTime::where('attendance_id', $attendance->id)->whereNull('break_end')->first();
    }

    return view('index', compact('attendance', 'ongoingBreak'));
}



}
