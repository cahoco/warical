@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="main-wrapper">

@php
    $currentMonth = \Carbon\Carbon::now()->format('n');
    $startDate = \Carbon\Carbon::now()->startOfMonth()->format('n/j');
    $endDate = \Carbon\Carbon::now()->endOfMonth()->format('n/j');
@endphp

    {{-- 合計支出と清算表示 --}}
    <div class="section summary-section">
        <h3 class="summary-title">{{ $currentMonth }}月の合計支出：<span class="amount">¥{{ number_format($totalAmount) }}</span></h3>
        <p class="settlement">
            {{ $payer }} → {{ $receiver }} ¥{{ number_format($settlementAmount) }}
        </p>
    </div>

    {{-- メモエリア --}}
    <div class="section memo-section">
        <form action="{{ route('memo.store') }}" method="POST">
            @csrf

            <div class="memo-label-wrapper">
                <span class="memo-label">MEMO</span>
                <small id="save-status" class="save-status"></small>
            </div>

            <textarea name="memo" id="memo" rows="4" class="memo-textarea">{{ old('memo', $memo->content ?? '') }}</textarea>
        </form>
    </div>

    {{-- 支出履歴 --}}
    <div class="section history-section">
        <h3 class="history-title">{{ $currentMonth }}月の支出履歴</h3>
        <p class="date-range">期間：{{ $startDate }}〜{{ $endDate }}</p>
        <ul class="history-list">
            @forelse($expenses as $expense)
                <li>
                    {{ $expense->date }} / {{ $expense->category }} / ¥{{ number_format($expense->amount) }} / {{ $expense->payer }}
                </li>
            @empty
                <li>支出がまだ記録されていません</li>
            @endforelse
        </ul>
    </div>

</div>
@endsection

@section('footer')
<div class="fixed-footer">
    <div class="footer-right">
        <a href="{{ route('calendar.index') }}" class="footer-btn calendar">カレンダー</a>
    </div>
    <a href="{{ route('records.create') }}" class="footer-btn input">入力</a>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.getElementById('memo');
    const status = document.getElementById('save-status');
    let timeout = null;

    textarea.addEventListener('input', () => {
        status.textContent = "保存中...";

        if (timeout) clearTimeout(timeout);

        timeout = setTimeout(() => {
            fetch("{{ route('memo.autoSave') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ memo: textarea.value })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "saved") {
                    status.textContent = "保存しました";
                    status.classList.remove("fade-out");
                    setTimeout(() => {
                        status.classList.add("fade-out");
                    }, 3000);
                } else {
                    status.textContent = "保存できませんでした";
                }
            })
            .catch(() => {
                status.textContent = "エラーが発生しました";
            });
        }, 1000);
    });
});
</script>
@endsection

