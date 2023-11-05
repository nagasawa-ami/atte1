<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;
    
     protected $fillable = ['user_id', 'date', 'work_start', 'work_end'];
     protected $dates = ['work_start', 'work_end']; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breakTimes()
    {
        return $this->hasMany(BreakTime::class);
    
    }


// 休憩時間合計のアクセサ
public function getTotalBreakAttribute()
{
    return $this->breakTimes->sum(function($break) {
        $start = Carbon::parse($break->break_start);
        $end = Carbon::parse($break->break_end);
        return $end->diffInSeconds($start);
    });
}

// 勤務時間合計のアクセサ
public function getTotalWorkAttribute()
{
    $start = Carbon::parse($this->work_start);
    $end = $this->work_end ? Carbon::parse($this->work_end) : Carbon::now();
    // 休憩時間を引いて勤務時間を計算
    return $end->diffInSeconds($start) - $this->getTotalBreakAttribute();
}

}

