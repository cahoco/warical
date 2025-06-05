@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="profile-wrapper">
    <h2 class="profile-title">プロフィール編集</h2>

    {{-- 🔽 タブメニュー --}}
    <div class="tab-menu">
        <button class="tab-button active" data-tab="a">
            {{ $profile->a_name ?? 'Aさん' }}
        </button>
        <button class="tab-button" data-tab="b">
            {{ $profile->b_name ?? 'Bさん' }}
        </button>
    </div>

    <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
        @csrf

        {{-- 🔽 Aさんタブ --}}
        <div class="tab-content active" id="tab-a">
            <label for="a_name">名前</label>
            <input type="text" id="a_name" name="a_name" value="{{ old('a_name', $profile->a_name ?? '') }}">

            <label for="a_birthday">誕生日</label>
            <input type="date" id="a_birthday" name="a_birthday" value="{{ old('a_birthday', $profile->a_birthday ?? '') }}">

            <label for="a_weak_foods">苦手な食べ物</label>
            <textarea id="a_weak_foods" name="a_weak_foods" rows="3">{{ old('a_disliked_foods', $profile->a_disliked_foods ?? '') }}</textarea>
        </div>

        {{-- 🔽 Bさんタブ --}}
        <div class="tab-content" id="tab-b">
            <label for="b_name">名前</label>
            <input type="text" id="b_name" name="b_name" value="{{ old('b_name', $profile->b_name ?? '') }}">

            <label for="b_birthday">誕生日</label>
            <input type="date" id="b_birthday" name="b_birthday" value="{{ old('b_birthday', $profile->b_birthday ?? '') }}">

            <label for="b_disliked_foods">苦手な食べ物</label>
            <textarea id="b_disliked_foods" name="b_disliked_foods" rows="3">{{ old('b_disliked_foods', $profile->b_disliked_foods ?? '') }}</textarea>
        </div>

        <button type="submit" class="save-button">保存</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // タブ切り替え処理
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.tab-button');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // アクティブ切り替え
                tabs.forEach(t => t.classList.remove('active'));
                contents.forEach(c => c.classList.remove('active'));

                tab.classList.add('active');
                document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
            });
        });
    });
</script>
@endsection
