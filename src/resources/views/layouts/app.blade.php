<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>割り勘アプリ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}?v={{ time() }}">
    @yield('css')
</head>
<body>
    <!-- ▼ グラデーションヘッダー -->
    <div class="gradient-header d-flex justify-content-between align-items-center px-4 py-2">
        <!-- ▼ 左：設定ドロップダウン -->
        <div class="dropdown">
            <button class="dropdown-toggle header-button" data-bs-toggle="dropdown" aria-expanded="false">
                設定
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">プロフィール</a></li>
                <li><a class="dropdown-item" href="{{ url('/settings/payment') }}">支払い</a></li>
                <li><a class="dropdown-item" href="{{ route('categories.index') }}">カテゴリー</a></li>
            </ul>
        </div>

        <!-- ▼ 中央：ホームリンク -->
        <a href="{{ route('expenses.index') }}" class="header-link text-white fw-bold fs-4 text-decoration-none">
            my home
        </a>

        <!-- ▼ 右：旅行モードボタン -->
        <form action="{{ route('toggle.travel_mode') }}" method="POST">
            @csrf
            <button type="submit" class="header-button">旅行モード</button>
        </form>
    </div>

    @yield('content')
    @yield('footer')
    @yield('scripts')

    <!-- BootstrapのJS（ドロップダウン用） -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
