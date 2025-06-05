@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/calendar.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="calendar-wrapper">
    <h2>{{ now()->format('Y年n月') }}のカレンダー</h2>
    <table class="calendar-table">
        <tr>
            <th>日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th>
        </tr>
        @php
            $start = \Carbon\Carbon::now()->startOfMonth();
            $end = \Carbon\Carbon::now()->endOfMonth();
            $current = $start->copy()->startOfWeek();
        @endphp

        @while ($current <= $end)
            <tr>
                @for ($i = 0; $i < 7; $i++)
                    <td>
                        @if ($current->month == now()->month)
                            <div>{{ $current->day }}</div>
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
