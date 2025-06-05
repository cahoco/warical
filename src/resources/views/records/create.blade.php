@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="main-wrapper">
    <h2 style="text-align: center;">支出の登録</h2>

    @php
        $members = ['Aさん', 'Bさん'];
        $categories = ['食費', '交通費', '日用品', '娯楽', '交際費'];
    @endphp

    <form method="POST" action="{{ route('expenses.store') }}">
        @csrf

        <!-- 日付 -->
        <div class="mb-3">
            <label>日付</label>
            <input type="date" name="date" class="form-control" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
        </div>

        <!-- 支払者（ボタン選択） -->
        <div class="mb-3">
            <label>誰が支払いましたか？</label>
            <div class="payer-radio-group">
                @foreach ($members as $member)
                    <label class="payer-radio-wrapper">
                        <input type="radio" name="payer" value="{{ $member }}">
                        <span class="custom-radio"></span>
                        {{ $member }}
                    </label>
                @endforeach
            </div>
        </div>

        <!-- 合計金額 -->
        <div class="mb-3">
            <label>合計金額</label>
            <input type="number" name="amount" class="form-control">
        </div>

        <!-- カテゴリー -->
        <div class="mb-3">
            <label>カテゴリー</label>
            <select name="category" class="form-control">
                <option value="">選択してください</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
        </div>

        <!-- 割り方 -->
        <div class="mb-3">
            <label>割り方</label>
            <div class="radio-group">
                <label class="radio-wrapper">
                    <input type="radio" name="split_type" value="equal" checked>
                    <span class="custom-radio"></span>
                    ぴったり割り勘
                </label>

                <label class="radio-wrapper">
                    <input type="radio" name="split_type" value="custom">
                    <span class="custom-radio"></span>
                    個別金額入力
                </label>

                <label class="radio-wrapper">
                    <input type="radio" name="split_type" value="percentage">
                    <span class="custom-radio"></span>
                    割合で分担
                </label>
            </div>
        </div>

        <!-- 個別金額 -->
        <div id="custom-fields" class="mb-3" style="display: none;">
            <label>各メンバーの金額</label>
            <div class="mb-2">
                <label>Aさん</label>
                <input type="number" name="amounts[Aさん]" id="amount_a" class="form-control">
            </div>
            <div class="mb-2">
                <label>Bさん</label>
                <input type="number" name="amounts[Bさん]" id="amount_b" class="form-control">
            </div>
        </div>

        <!-- 割合入力 -->
        <div id="percentage-fields" class="mb-3" style="display: none;">
            <label>各メンバーの割合（%）</label>
            <div class="mb-2">
                <label>Aさん</label>
                <select name="percentages[Aさん]" id="percent_a" class="form-control">
                    <option value="">--</option>
                    @for ($i = 0; $i <= 100; $i += 10)
                        <option value="{{ $i }}" {{ $i == 50 ? 'selected' : '' }}>{{ $i }}%</option>
                    @endfor
                </select>
            </div>
            <div class="mb-2">
                <label>Bさん</label>
                <select name="percentages[Bさん]" id="percent_b" class="form-control">
                    <option value="">--</option>
                    @for ($i = 0; $i <= 100; $i += 10)
                        <option value="{{ $i }}" {{ $i == 50 ? 'selected' : '' }}>{{ $i }}%</option>
                    @endfor
                </select>
            </div>

            <div class="mt-3">
                <label>自動計算された金額</label>
                <input type="text" id="amount_a_result" class="form-control mb-2" readonly>
                <input type="text" id="amount_b_result" class="form-control" readonly>
            </div>
        </div>

        <!-- 登録ボタン -->
        <div class="mt-4">
            <button type="submit" class="btn btn-primary w-100">登録する</button>
        </div>

        <!-- 一覧に戻るボタン -->
        <div class="mt-4" style="text-align: center;">
            <a href="{{ route('expenses.index') }}">
                <button type="button" class="btn btn-secondary">一覧に戻る</button>
            </a>
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

    function updatePercentages(changedBy) {
        const total = parseInt(totalInput.value) || 0;
        const aPercent = parseInt(percentA.value);
        const bPercent = parseInt(percentB.value);

        if (changedBy === 'a' && !isNaN(aPercent)) {
            percentB.value = Math.max(0, 100 - aPercent);
        } else if (changedBy === 'b' && !isNaN(bPercent)) {
            percentA.value = Math.max(0, 100 - bPercent);
        }

        const finalA = parseInt(percentA.value) || 0;
        const finalB = 100 - finalA;
        amountAResult.value = 'Aさん：\u00a5' + Math.floor((finalA / 100) * total);
        amountBResult.value = 'Bさん：\u00a5' + Math.floor((finalB / 100) * total);
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