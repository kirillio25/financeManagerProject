<div class="card shadow-sm">
    <div class="card-body">
        <div class="mb-3">
            <h5 class="mb-2">Профиль</h5>
            <p class="text-muted mb-0">
                Обновите информацию профиля и email-адрес.
            </p>
        </div>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-3">
                <label for="name" class="form-label">Имя</label>
                <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-muted small">
                            Ваш email не подтверждён.
                            <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">Нажмите здесь, чтобы выслать письмо повторно</button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="text-success small mt-1">
                                Новая ссылка для подтверждения отправлена на ваш email.
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-success">Сохранить</button>

                @if (session('status') === 'profile-updated')
                    <p class="text-muted small mb-0" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">
                        Сохранено.
                    </p>
                @endif
            </div>
        </form>
    </div>
</div>
