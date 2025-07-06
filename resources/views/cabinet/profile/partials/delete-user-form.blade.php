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
