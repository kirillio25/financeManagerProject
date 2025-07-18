@extends('layouts.app')

@section('title', 'Счета')

@section('content')

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Личные счета</h3>
                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                            data-bs-target="#addAccountModal">Добавить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Список счетов</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Примечание</th>
                                    <th>Баланс</th>
                                    <th class="text-center">Действие</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accounts as $account)
                                    <tr>
                                        <td>{{ $account['id'] }}</td>
                                        <td>{{ $account['name'] }}</td>
                                        <td>{{ $account['note'] }}</td>
                                        <td>{{ number_format($account['balance'], 2, '.', ' ') }} ₸</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                                                    data-bs-target="#editAccountModal" data-id="{{ $account['id'] }}"
                                                    data-name="{{ $account['name'] }}" data-note="{{ $account['note'] }}">
                                                    <i class="bi bi-pencil text-primary fs-5"></i>
                                                </a>

                                                <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                                                    data-bs-target="#deleteAccountModal" data-id="{{ $account['id'] }}">
                                                    <i class="bi bi-trash text-danger fs-5"></i>
                                                </a>

                                            </div>

                                    </tr>
                                @endforeach
                                @if($accounts->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Нет данных</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно редактирования -->
    <div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('accounts.update', 'id') }}" id="editAccountForm"
                data-base-action="{{ route('accounts.update', 'id') }}">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать счёт</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Название</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Примечание</label>
                            <textarea name="note" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Сохранить</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Модальное окно добавления счёта -->
    <div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('accounts.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAccountModalLabel">Добавить счёт</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Название</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Примечание</label>
                            <textarea name="note" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Сохранить</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Модальное окно удаления -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('accounts.destroy', 'id') }}" id="deleteAccountForm"
                data-base-action="{{ route('accounts.destroy', 'id') }}">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Удаление счёта</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Вы уверены, что хотите удалить этот счёт? Это действие необратимо.
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Удалить</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('editAccountModal');
            const form = document.getElementById('editAccountForm');
            const nameInput = form.querySelector('input[name="name"]');
            const noteInput = form.querySelector('textarea[name="note"]');

            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const note = button.getAttribute('data-note');

                form.action = form.getAttribute('data-base-action').replace('id', id);
                nameInput.value = name ?? '';
                noteInput.value = note ?? '';
            });

            modal.addEventListener('hidden.bs.modal', function () {
                form.action = form.getAttribute('data-base-action');
                form.reset();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteModal = document.getElementById('deleteAccountModal');
            const deleteForm = document.getElementById('deleteAccountForm');

            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                deleteForm.action = deleteForm.getAttribute('data-base-action').replace('id', id);
            });

            deleteModal.addEventListener('hidden.bs.modal', function () {
                deleteForm.action = deleteForm.getAttribute('data-base-action');
            });
        });
    </script>
@endpush
