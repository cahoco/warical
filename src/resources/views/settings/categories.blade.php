@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/setting.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="setting-wrapper">
    <h2 class="setting-title">カテゴリ設定</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST" class="mb-4 d-flex gap-2">
        @csrf
        <input type="text" name="name" class="form-control" placeholder="カテゴリ名" required>
        <button type="submit" class="btn btn-primary">追加</button>
    </form>

    <ul class="list-group">
        @forelse ($categories as $category)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $category->name }}
                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('削除しますか？');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">削除</button>
                </form>
            </li>
        @empty
            <li class="list-group-item">カテゴリがありません</li>
        @endforelse
    </ul>
</div>
@endsection
