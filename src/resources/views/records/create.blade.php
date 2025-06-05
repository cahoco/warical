@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="main-wrapper">
    <h2 class="profile-title">支出の登録</h2>

    @php
        $memberNames = [
            'A' => $profile->a_name ?? 'Aさん',
            'B' => $profile->b_name ?? 'Bさん',
        ];
    @endphp

    <form method="POST" action="{{ route('expenses.store') }}" id="expense-form">
        @csrf

        <!-- 日付 -->
        <div class="mb-3">
            <label>日付</label>
            <input type="date" name="date" class="form-control" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
        </div>

        <!-- 支払者 -->
        <div class="mb-3">
            <label>誰が支払いましたか？</label>
            <div class="payer-radio-group">
                @foreach ($memberNames as $key => $name)
                    <label class="payer-radio-wrapper">
                        <input type="radio" name="payer" value="{{ $name }}">
                        <span class="custom-radio"></span>
                        {{ $name }}
                    </label>
                @endforeach
            </div>
        </div>

        <!-- 合計金額 -->
        <div class="mb-3">
            <label>合計金額</label>
            <input type="number" name="amount" id="total_amount" class="form-control">
        </div>

        <!-- カテゴリー -->
        <div class="mb-3">
            <label>カテゴリー</label>
            <select name="category" class="form-control" required>
                <option value="">選択してください</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- 割り方 -->
        <div class="mb-3">
            <label>割り方</label>
            <div class="radio-group">
                <label class="radio-wrapper">
                    <input type="radio" name="split_type" value="equal">
                    <span class="custom-radio"></span>
                    ぴったり割り勘
                </label>

                <label class="radio-wrapper">
                    <input type="radio" name="split_type" value="custom">
                    <span class="custom-radio"></span>
                    個別金額入力
                </label>

                <label class="radio-wrapper">
                    <input type="radio" name="split_type" value="percentage" checked>
                    <span class="custom-radio"></span>
                    割合で分担
                </label>
            </div>
        </div>

        <!-- 個別金額 -->
        <div id="custom-fields" class="mb-3" style="display: none;">
            <label>各メンバーの金額</label>
            <div class="mb-2">
                <label>{{ $memberNames['A'] }}</label>
                <input type="number" name="amounts[{{ $memberNames['A'] }}]" id="amount_a" class="form-control">
            </div>
            <div class="mb-2">
                <label>{{ $memberNames['B'] }}</label>
                <input type="number" name="amounts[{{ $memberNames['B'] }}]" id="amount_b" class="form-control">
            </div>
        </div>

        <!-- 割合入力 -->
        <div id="percentage-fields" class="mb-3">
            <label>各メンバーの割合（%）</label>
            <div class="mb-2">
                <label>{{ $memberNames['A'] }}</label>
                <select name="percentages[{{ $memberNames['A'] }}]" id="percent_a" class="form-control">
                    @for ($i = 0; $i <= 100; $i += 10)
                        <option value="{{ $i }}" {{ $i == $setting->a_share ? 'selected' : '' }}>{{ $i }}%</option>
                    @endfor
                </select>
            </div>
            <div class="mb-2">
                <label>{{ $memberNames['B'] }}</label>
                <select name="percentages[{{ $memberNames['B'] }}]" id="percent_b" class="form-control">
                    @for ($i = 0; $i <= 100; $i += 10)
                        <option value="{{ $i }}" {{ $i == $setting->b_share ? 'selected' : '' }}>{{ $i }}%</option>
                    @endfor
                </select>
            </div>

            <div class="mt-3">
                <label>自動計算された金額</label>
                    <input type="text" id="amount_a_result" class="form-control mb-2" readonly data-name="{{ $memberNames['A'] }}">
                    <input type="text" id="amount_b_result" class="form-control" readonly data-name="{{ $memberNames['B'] }}">
            </div>

    </form>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="split_type"]');
    const customFields = document.getElementById('custom-fields');
    const percentageFields = document.getElementById('percentage-fields');
    const totalInput = document.getElementById('total_amount');
    const amountA = document.getElementById('amount_a');
    const amountB = document.getElementById('amount_b');
    const percentA = document.getElementById('percent_a');
    const percentB = document.getElementById('percent_b');
    const amountAResult = document.getElementById('amount_a_result');
    const amountBResult = document.getElementById('amount_b_result');

    function updateView() {
        const selected = document.querySelector('input[name="split_type"]:checked').value;
        customFields.style.display = (selected === 'custom') ? 'block' : 'none';
        percentageFields.style.display = (selected === 'percentage') ? 'block' : 'none';
        if (selected === 'percentage') updatePercentages();
    }

    function updateCustomAmounts(changedBy) {
        const total = parseInt(totalInput.value) || 0;
        const aRaw = amountA.value;
        const bRaw = amountB.value;
        const a = parseInt(aRaw);
        const b = parseInt(bRaw);

        if (changedBy === 'a') {
            if (aRaw === '') {
                amountB.value = '';
            } else {
                amountB.value = Math.max(total - a, 0);
            }
        }

        if (changedBy === 'b') {
            if (bRaw === '') {
                amountA.value = '';
            } else {
                amountA.value = Math.max(total - b, 0);
            }
        }
    }

    function updatePercentages(changedBy = null) {
        const total = parseInt(totalInput.value) || 0;
        let aPercent = parseInt(percentA.value) || 0;
        let bPercent = parseInt(percentB.value) || 0;

        if (changedBy === 'a') {
            percentB.value = 100 - aPercent;
        } else if (changedBy === 'b') {
            percentA.value = 100 - bPercent;
        }

        aPercent = parseInt(percentA.value) || 0;
        bPercent = 100 - aPercent;

        amountAResult.value = amountAResult.dataset.name + '：¥' + Math.floor((aPercent / 100) * total);
        amountBResult.value = amountBResult.dataset.name + '：¥' + Math.floor((bPercent / 100) * total);
    }

    radios.forEach(r => r.addEventListener('change', updateView));
    updateView();

    amountA.addEventListener('input', () => updateCustomAmounts('a'));
    amountB.addEventListener('input', () => updateCustomAmounts('b'));
    totalInput.addEventListener('input', () => {
        updateCustomAmounts();
        updatePercentages();
    });
    percentA.addEventListener('change', () => updatePercentages('a'));
    percentB.addEventListener('change', () => updatePercentages('b'));
});
</script>
@endsection

@section('footer')
<div class="fixed-footer">
    <div class="footer-right">
        <a href="{{ route('expenses.index') }}" class="footer-btn calendar">一覧に戻る</a>
    </div>
    <button type="submit" form="expense-form" class="footer-btn input">登録</button>
</div>
@endsection
