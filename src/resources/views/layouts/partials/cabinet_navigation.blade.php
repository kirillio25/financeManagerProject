<nav class="mt-2">
    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

        <!-- Статистика -->
        <li class="nav-item {{ request()->routeIs('monthlyStats.*', 'yearlyStats', 'allTimeStats') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('monthlyStats.*', 'yearlyStats', 'allTimeStats') ? 'bg-light text-dark' : '' }}">
                <i class="nav-icon bi bi-speedometer"></i>
                <p>
                    Статистика
                    <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('monthlyStats.index') }}" class="nav-link {{ request()->routeIs('monthlyStats.index') ? 'active bg-success text-white' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>За месяц</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('yearlyStats') }}" class="nav-link {{ request()->routeIs('yearlyStats') ? 'active bg-success text-white' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>За год</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('allTimeStats') }}" class="nav-link {{ request()->routeIs('allTimeStats') ? 'active bg-success text-white' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>За все время</p>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Долги -->
        <li class="nav-item {{ request()->routeIs('debts.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('debts.*') ? 'bg-light text-dark' : '' }}">
                <i class="nav-icon bi bi-pencil-square"></i>
                <p>
                    Долги
                    <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('debts.index') }}" class="nav-link {{ request()->routeIs('debts.index') ? 'active bg-success text-white' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Мои долги</p>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Прочее -->
        <li class="nav-header">Прочее</li>
        <li class="nav-item">
            <a href="{{ route('transactionHistory.index') }}" class="nav-link {{ request()->routeIs('transactionHistory.index') ? 'active bg-success text-white' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>История транзакций</p>
            </a>
        </li>
    </ul>
</nav>
