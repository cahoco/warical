@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/setting.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="setting-wrapper">
    <h2 class="profile-title">支払い設定</h2>

    @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <form action="{{ route('settings.payment.update') }}" method="POST" class="setting-form">
        @csrf

        @php
            $nameA = $profile->a_name ?? 'Aさん';
            $nameB = $profile->b_name ?? 'Bさん';
        @endphp

        <div class="form-group mb-4">
            <label for="a_share">{{ $nameA }} の支払い割合（%）</label>
            <input type="number" name="a_share" id="a_share" value="{{ old('a_share', $setting->a_share) }}" class="form-control" min="0" max="100" required>
        </div>

        <div class="form-group mb-4">
            <label for="b_share">{{ $nameB }} の支払い割合（%）</label>
            <input type="number" name="b_share" id="b_share" value="{{ old('b_share', $setting->b_share) }}" class="form-control" min="0" max="100" required>
        </div>

        <div class="text-center">
            <button type="submit" class="save-button">保存</button>
        </div>
    </form>
</div>
@endsection
