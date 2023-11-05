@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="container">


  {{-- "前へ" と "次へ" のリンク --}}
<div class="date-pagination">
    <a href="{{ route('report.list', ['date' => $prevDate]) }}">＜</a>
    <span>{{ $date }}</span>
    <a href="{{ route('report.list', ['date' => $nextDate]) }}">＞</a>
</div>



    {{-- 勤務記録テーブル --}}
    <div class="attendance-table">
        <table class="attendance-table__inner">
            <thead>
                <tr class="attendance-table__row">
                    <th class="attendance-table__header">名前</th>
                    <th class="attendance-table__header">勤務開始</th>
                    <th class="attendance-table__header">勤務終了</th>
                    <th class="attendance-table__header">休憩時間</th>
                    <th class="attendance-table__header">勤務時間</th>
                </tr>
            </thead>
            <tbody>
                {{-- 勤務記録データの動的表示 --}}
                @foreach ($attendances as $attendance)
                <tr class="attendance-table__row">
                  <td class="attendance-table__item">{{ $attendance->user->name }}</td>
                  <td class="attendance-table__item">{{ $attendance->work_start ? \Carbon\Carbon::parse($attendance->work_start)->format('H:i:s') : '---' }}</td>
                  <td class="attendance-table__item">{{ $attendance->work_end ? \Carbon\Carbon::parse($attendance->work_end)->format('H:i:s') : '---' }}</td>
                  <td class="attendance-table__item">{{ gmdate('H:i:s', $attendance->total_break) }}</td>
                  <td class="attendance-table__item">{{ gmdate('H:i:s', $attendance->total_work) }}</td>
                </tr>
              
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ページネーションリンク --}}
    <div class="pagination">
        {{ $attendances->appends(request()->query())->links() }}
    </div>
</div>
@endsection