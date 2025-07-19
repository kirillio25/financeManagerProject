@extends('layouts.app')

@section('title', 'Категории расходов')

@section('content')

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Категории расходов</h3>
                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#addDebtModal">Добавить</button>
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
                        <h3 class="card-title">Список категорий</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap align-middle">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Я должен или мне</th>
                                <th>Кто/Кому</th>
                                <th>Сумма($)</th>
                                <th>Способ связи</th>
                                <th>Описание</th>
                                <th>Статус</th>
                                <th>Дата</th>
                                <th class="text-center">Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($debts as $debt)
                                <tr>
                                    <td>{{ $debt->id }}</td>
                                    <td>{{ $debt->debt_direction == 0 ? 'Я должен' : 'Мне должны' }}</td>
                                    <td>{{ $debt->name }}</td>
                                    <td>{{ $debt->amount }}</td>
                                    <td>{{ $debt->contact_method }}</td>
                                    <td>{{ $debt->description }}</td>
                                    <td>
                                        <form action="{{ route('debts.toggleStatus', $debt->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $debt->status == 1 ? 'btn-danger' : 'btn-success' }}">
                                                {{ $debt->status == 1 ? 'Активный' : 'Закрыт' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ $debt->created_at }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <a href="#" class="text-decoration-none"
                                               data-bs-toggle="modal"
                                               data-bs-target="#editDebtModal"
                                               data-id="{{ $debt->id }}"
                                               data-name="{{ $debt->name }}"
                                               data-debt_direction="{{ $debt->debt_direction }}"
                                               data-amount="{{ $debt->amount }}"
                                               data-contact_method="{{ $debt->contact_method }}"
                                               data-description="{{ $debt->description }}">
                                                <i class="bi bi-pencil text-primary fs-5"></i>
                                            </a>

                                            <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                                               data-bs-target="#deleteDebtModal"
                                               data-id="{{ $debt->id }}">
                                                <i class="bi bi-trash text-danger fs-5"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if($debts->isEmpty())
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Нет данных</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $debts->links() }}
        </div>
    </div>

    <!-- Modal: Редактирование -->
    <div class="modal fade" id="editDebtModal" tabindex="-1" aria-labelledby="editDebtModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('debts.update', 'id') }}" id="editDebtForm"
                  data-base-action="{{ route('debts.update', 'id') }}">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать долг</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="mb-3">
                            <label class="form-label">Название</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Кому должен</label>
                            <select name="debt_direction" class="form-select" required>
                                <option value="0">Я должен</option>
                                <option value="1">Мне должны</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Сумма в тг</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Способ связи</label>
                            <input type="text" name="contact_method" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Описание</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
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


    <!-- Modal: Добавления -->
    <div class="modal fade" id="addDebtModal" tabindex="-1" aria-labelledby="addDebtModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('debts.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAccountModalLabel">Добавить категорию</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Название</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Кому должен</label>
                            <select name="debt_direction" class="form-select" required>
                                <option value="0">Я должен</option>
                                <option value="1">Мне должны</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Сумма в тг</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Способ связи</label>
                            <input type="text" name="contact_method" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Описание</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
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

    <!-- Modal: Удаление -->
    <div class="modal fade" id="deleteDebtModal" tabindex="-1" aria-labelledby="deleteDebtModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('debts.destroy', 'id') }}" id="deleteDebtForm"
                  data-base-action="{{ route('debts.destroy', 'id') }}">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Удаление долга</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Вы уверены, что хотите удалить этот долг? Это действие необратимо.
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
            const addModal = document.getElementById('addDebtModal');
            const addForm = addModal.querySelector('form');

            addModal.addEventListener('hidden.bs.modal', function () {
                addForm.reset();
            });

            const deleteModal = document.getElementById('deleteDebtModal');
            const deleteForm = document.getElementById('deleteDebtForm');

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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editModal = document.getElementById('editDebtModal');
            const editForm = document.getElementById('editDebtForm');

            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const debt_direction = button.getAttribute('data-debt_direction');
                const amount = button.getAttribute('data-amount');
                const contact_method = button.getAttribute('data-contact_method');
                const description = button.getAttribute('data-description');
                const select = editForm.querySelector('[name="debt_direction"]');

                select.querySelectorAll('option').forEach(opt => {
                    opt.selected = (opt.value === debt_direction);
                });

                editForm.action = editForm.getAttribute('data-base-action').replace('id', id);
                editForm.querySelector('[name="name"]').value = name;
                editForm.querySelector('[name="debt_direction"]').value = debt_direction;
                editForm.querySelector('[name="amount"]').value = amount;
                editForm.querySelector('[name="contact_method"]').value = contact_method;
                editForm.querySelector('[name="description"]').value = description;
            });

            editModal.addEventListener('hidden.bs.modal', function () {
                editForm.reset();
                editForm.action = editForm.getAttribute('data-base-action');
            });
        });
    </script>
@endpush

