@extends('layouts.app')

@section('title', 'Категории расходов')

@section('content')

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Категории расходов</h3>
{{--                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"--}}
{{--                            data-bs-target="#addAccountModal">Добавить</button>--}}
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
                        <h3 class="card-title">Список транзакций</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap align-middle">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Дата</th>
                                <th>Тип</th>
                                <th>Сумма($)</th>
                                <th>Категория</th>
                                <th>Счёт</th>
                                <th class="text-center">Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction['id'] }}</td>
                                    <td>{{ $transaction['date'] }}</td>
                                    <td>{{ $transaction['type'] }}</td>
                                    <td>{{ $transaction['amount'] }}</td>
                                    <td>{{ $transaction['category'] }}</td>
                                    <td>{{ $transaction['account'] }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
{{--                                            <a href="#" class="text-decoration-none" data-bs-toggle="modal"--}}
{{--                                               data-bs-target="#editTransactionModal"--}}
{{--                                               data-id="{{ $transaction['id'] }}">--}}
{{--                                                <i class="bi bi-pencil text-primary fs-5"></i>--}}
{{--                                            </a>--}}

                                            <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                                               data-bs-target="#deleteTransactionModal"
                                               data-id="{{ $transaction['id'] }}">
                                                <i class="bi bi-trash text-danger fs-5"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Нет данных</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal: Редактирование -->
{{--    <div class="modal fade" id="editTransactionModal" tabindex="-1" aria-labelledby="editTransactionModalLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog">--}}
{{--            <form method="POST" action="{{ route('transactionHistory.update', 'id') }}" id="editTransactionForm"--}}
{{--                  data-base-action="{{ route('transactionHistory.update', 'id') }}">--}}
{{--                @csrf--}}
{{--                @method('PUT')--}}
{{--                <div class="modal-content">--}}
{{--                    <div class="modal-header">--}}
{{--                        <h5 class="modal-title">Редактировать транзакцию</h5>--}}
{{--                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        <input type="hidden" name="id">--}}
{{--                        <div class="mb-3">--}}
{{--                            <label class="form-label">Сумма</label>--}}
{{--                            <input type="number" step="0.01" name="amount" class="form-control" required>--}}
{{--                        </div>--}}
{{--                        <div class="mb-3">--}}
{{--                            <label class="form-label">Дата</label>--}}
{{--                            <input type="datetime-local" name="date" class="form-control" required>--}}
{{--                        </div>--}}
{{--                        --}}{{-- при необходимости добавь поля account_id, category_id, type_id --}}
{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="submit" class="btn btn-success">Сохранить</button>--}}
{{--                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}

    <!-- Modal: Удаление -->
    <div class="modal fade" id="deleteTransactionModal" tabindex="-1" aria-labelledby="deleteTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST"
                  id="deleteTransactionForm"
                  action="{{ route('transactionHistory.destroy', ['transaction' => '__ID__']) }}"
                  data-base-action="{{ route('transactionHistory.destroy', ['transaction' => '__ID__']) }}">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Удаление транзакции</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Вы уверены, что хотите удалить эту транзакцию?
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
            const deleteModal = document.getElementById('deleteTransactionModal');
            const deleteForm = document.getElementById('deleteTransactionForm');

            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                deleteForm.action = deleteForm.getAttribute('data-base-action').replace('__ID__', id);
            });

            deleteModal.addEventListener('hidden.bs.modal', function () {
                deleteForm.action = deleteForm.getAttribute('data-base-action');
            });
        });
    </script>
@endpush

