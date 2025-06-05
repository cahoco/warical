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

    <div class="gradient-header">
        <a href="{{ route('expenses.index') }}" class="header-link">my home</a>
    </div>

    @yield('content')

    @yield('footer')

    @yield('scripts')

</body>
</html>
