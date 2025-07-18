<!doctype html>
<html lang="ru">
<head>
    @include('layouts.partials.head')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    @include('layouts.partials.navbar')
    @include('layouts.partials.sidebar')
    <main class="app-main">@yield('content')</main>
    @include('layouts.partials.footer')
</div>
@include('layouts.partials.scripts')
@stack('scripts')
</body>
</html>
