<!-- resources/views/layouts/partials/sidebar.blade.php -->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('monthlyStats.index') }}" class="brand-link">
            <img
                src="{{ asset('cabinet/assets/img/Logo.png') }}"
                alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow"
            />
            <span class="brand-text fw-light">NummoFin</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        @include('layouts.partials.cabinet_navigation')
    </div>
</aside>
