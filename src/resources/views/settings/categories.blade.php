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

    {{-- 新規追加フォーム --}}
    <form action="{{ route('categories.store') }}" method="POST" class="mb-4 d-flex gap-2">
        @csrf
        <input type="text" name="name" class="form-control" placeholder="カテゴリ名" required>
        <button type="submit" class="btn btn-primary">追加</button>
    </form>

    {{-- 一覧と編集フォーム --}}
    <ul class="list-group">
        @forelse ($categories as $category)
            <li class="list-group-item">
                @if(request()->get('edit') == $category->id)
                    {{-- 編集フォーム --}}
                    <form action="{{ route('categories.update', $category->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                        @csrf
                        @method('PATCH')
                        <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
                        <button type="submit" class="btn btn-success btn-sm">保存</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">キャンセル</a>
                    </form>
                @else
                    {{-- 表示・操作 --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ $category->name }}</span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('categories.index', ['edit' => $category->id]) }}" class="btn btn-sm btn-outline-primary">編集</a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">削除</button>
                            </form>
                        </div>
                    </div>
                @endif
            </li>
        @empty
            <li class="list-group-item">カテゴリがありません</li>
        @endforelse
    </ul>
</div>
@endsection
