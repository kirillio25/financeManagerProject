<div class="card shadow-sm">
    <div class="card-body">
        <div class="mb-3">
            <h5 class="mb-2">Обновить пароль</h5>
            <p class="text-muted mb-0">
                Убедитесь, что ваш пароль надёжный и уникальный.
            </p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="update_password_current_password" class="form-label">Текущий пароль</label>
                <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
                @error('current_password', 'updatePassword')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="update_password_password" class="form-label">Новый пароль</label>
                <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password">
                @error('password', 'updatePassword')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="update_password_password_confirmation" class="form-label">Подтвердите пароль</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                @error('password_confirmation', 'updatePassword')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-success">Сохранить</button>

                @if (session('status') === 'password-updated')
                    <p class="text-muted small mb-0" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">
                        Сохранено.
                    </p>
                @endif
            </div>
        </form>
    </div>
</div>
