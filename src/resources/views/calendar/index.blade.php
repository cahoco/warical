@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/calendar.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="calendar-wrapper">
    @php
        use Carbon\Carbon;
        $carbonDate = Carbon::parse($date ?? now());
        $prevMonth = $carbonDate->copy()->subMonth()->format('Y-m-01');
        $nextMonth = $carbonDate->copy()->addMonth()->format('Y-m-01');

        $start = $carbonDate->copy()->startOfMonth();
        $end = $carbonDate->copy()->endOfMonth();
        $current = $start->copy()->startOfWeek();
    @endphp

    <div class="month-card">
        <a href="{{ url('/calendar?date=' . $prevMonth) }}" class="prev-month">
            <img src="{{ asset('storage/images/left-arrow.png') }}" alt="前月" class="arrow-icon"> 前月
        </a>
        <div class="current-month">
            <img src="{{ asset('storage/images/calender-icon.png') }}" alt="カレンダー" class="calender-icon">
            {{ $carbonDate->format('Y/m') }}
        </div>
        <a href="{{ url('/calendar?date=' . $nextMonth) }}" class="next-month">
            翌月 <img src="{{ asset('storage/images/right-arrow.png') }}" alt="翌月" class="arrow-icon">
        </a>
    </div>

    <table class="calendar-table">
        <tr>
            <th>日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th>
        </tr>

        @while ($current <= $end)
            <tr>
                @for ($i = 0; $i < 7; $i++)
                    <td>
                        @if ($current->month === $carbonDate->month)
                            <div class="day-cell">
                                <span class="{{ $current->isToday() ? 'today' : '' }}">{{ $current->day }}</span>

                                {{-- 誕生日などのイベントがある場合 --}}
                                @if (!empty($events[$current->toDateString()]))
                                    <span class="event-label">{{ $events[$current->toDateString()] }}</span>
                                @endif

                            </div>

                            {{-- 支出がある場合 --}}
                            @if ($expenses->has($current->toDateString()))
                                <div class="amount">¥{{ number_format($expenses[$current->toDateString()]->total) }}</div>
                            @endif
                        @endif
                        @php $current->addDay(); @endphp
                    </td>

                @endfor
            </tr>
        @endwhile

    </table>
</div>
@endsection
