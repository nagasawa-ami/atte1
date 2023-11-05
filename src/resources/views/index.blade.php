@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    @if(session('message'))
    <div class="attendance__alert--success">
        {{ session('message')}}
    </div>
    @endif
</div>

<div class="login_name">
    <h3>{{ Auth::user()->name }}さんお疲れ様です。 </h3>
</div>

<div class="attendance__content">
  <div class="attendance__panel">
    <form class="attendance__button" action="{{ route('attendance.startWork')  }}" method="post">
    @csrf
    <button class="attendance__button-submit" type="submit" {{ $attendance ? 'disabled' : '' }}>勤務開始</button>
</form>

<form class="attendance__button" action="{{ route('attendance.endWork')  }}" method="post">
    @csrf
    <button class="attendance__button-submit" type="submit" {{ is_null($attendance) || is_null($attendance->work_start) || !is_null($attendance->work_end) || $ongoingBreak ? 'disabled' : '' }}>勤務終了</button>
</form>

<form class="attendance__button" action="{{ route('break.startBreak') }}" method="post">
    @csrf
    <button class="attendance__button-submit" type="submit" {{ is_null($attendance) || is_null($attendance->work_start) || !is_null($attendance->work_end) || $ongoingBreak ? 'disabled' : '' }}>休憩開始</button>
</form>

<form class="attendance__button" action="{{ route('break.endBreak', $ongoingBreak->id ?? null) }}" method="post">
    @csrf
    <button class="attendance__button-submit" type="submit" {{ $ongoingBreak ? '' : 'disabled' }}>休憩終了</button>
</form>
  </div>
</div>
@endsection

