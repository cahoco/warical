@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="main-wrapper">

@php
    $currentMonth = \Carbon\Carbon::now()->format('n');
@endphp

    {{-- 合計支出と清算表示 --}}
    <div class="section summary-section">
        <h3 class="summary-title">{{ $currentMonth }}月の支出：<span class="amount">¥{{ number_format($totalAmount) }}</span></h3>
        @php
            // すでにControllerで名前がセットされているので、そのまま使えばOK
            $payerName = $payer;
            $receiverName = $receiver;
        @endphp

        <p class="settlement">
            {{ $receiverName }} は {{ $payerName }} から ¥{{ number_format($settlementAmount) }} もらう
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

            <textarea name="memo" id="memo" rows="4" class="memo-textarea" placeholder="・たまご買う&#10;・パン屋で2000円の支払い（あとで入力しとくね！）">{{ old('memo', $memo->content ?? '') }}</textarea>
        </form>
    </div>

    <!-- 🔽 カテゴリ別円グラフ -->
    <div class="section chart-section">
        <canvas id="categoryPieChart" width="300" height="300"></canvas>
    </div>

    {{-- 支出履歴 --}}
    <ul class="history-list">
        @forelse($expenses as $expense)
            @php
                $displayName = $expense->payer;
            @endphp
            <li>
                {{ $expense->date }} / {{ $expense->category }} / ¥{{ number_format($expense->amount) }} / {{ $displayName }}
            </li>
        @empty
            <li>支出がまだ記録されていません</li>
        @endforelse
    </ul>


</div>
@endsection

@section('footer')
<div class="fixed-footer">
    <div class="footer-right">
        <a href="{{ route('calendar.index') }}" class="footer-btn calendar">カレンダー</a>
    </div>
    <a href="{{ route('expenses.create') }}" class="footer-btn input">入力</a>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 🔹 MEMO 自動保存
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

    // 🔹 Chart.js 円グラフ描画
    const ctx = document.getElementById('categoryPieChart').getContext('2d');

    const rawLabels = @json(array_keys($categoryData));
    const rawValues = @json(array_values($categoryData));

    // ラベルと値をペアにして降順でソート
    const pairedData = rawLabels.map((label, index) => ({
        label,
        value: rawValues[index]
    })).sort((a, b) => b.value - a.value);

    const sortedLabels = pairedData.map(item => item.label);
    const sortedValues = pairedData.map(item => item.value);

    // グラデーションカラー（濃→薄 #a1c4fd → #c2e9fb）
    const gradientColors = [
        '#a1c4fd', // 1番濃い
        '#adcdfd', // 中間1
        '#b8d6fe', // 中間2
        '#c2defe', // 中間3
        '#c2e9fb'  // 1番薄い
    ];

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: sortedLabels,
            datasets: [{
                data: sortedValues,
                backgroundColor: gradientColors.slice(0, sortedLabels.length),
                borderColor: '#fff',
                borderWidth: 2,
                hoverOffset: 8
            }]
        },
        options: {
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#444',
                        font: {
                            size: 13
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const value = context.parsed;
                            const label = context.label;
                            return `${label}: ¥${value.toLocaleString()}`;
                        }
                    }
                },
                title: {
                    display: true,
                    text: '{{ $currentMonth }}月のカテゴリ別支出',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: '#555'
                }
            }
        }
    });
});
</script>
@endsection
