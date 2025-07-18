@extends('layouts.app')

@section('title', 'Профиль')


@section('content')

<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
          <h3 class="mb-0">Профиль</h3>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
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

        </div>

        <div class="col-12 mt-4">
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
        </div>

        <div class="col-12 mt-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="mb-2">Экспорт данных</h5>
                        <p class="text-muted mb-0">
                            Убедитесь, что ваш пароль надёжный и уникальный.
                        </p>
                    </div>
                    <form action="{{ route('profile.export.sql') }}" method="GET">
                        <button type="submit" class="btn btn-outline-primary">
                            Экспортировать данные (SQL)
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 mt-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="mb-2">Импорт данных</h5>
                        <p class="text-muted mb-0">
                            Загрузите SQL-файл с экспортированными данными.
                        </p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cabinet.profile.import.sql') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Файл SQL</label>
                            <input type="file" name="sql_file" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Импортировать
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 mt-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="text-danger mb-2">Удаление аккаунта</h5>
                        <p class="text-muted mb-0">
                            После удаления аккаунта
                            все связанные данные будут безвозвратно удалены.
                            Скачайте необходимые данные заранее.
                        </p>
                    </div>

                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
                        Удалить аккаунт
                    </button>
                </div>
            </div>

            <!-- Модальное окно подтверждения удаления -->
            <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')

                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger" id="confirmUserDeletionModalLabel">Вы уверены?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                            </div>

                            <div class="modal-body">
                                <p class="mb-3">
                                    После удаления аккаунта все данные будут безвозвратно удалены. Введите пароль для подтверждения.
                                </p>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Пароль</label>
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Пароль" required>
                                    @error('password', 'userDeletion')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                <button type="submit" class="btn btn-danger">Удалить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')

@endpush
