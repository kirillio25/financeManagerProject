@extends('layouts.app')

@section('title', 'Статистика по месяцам')
@section('content')

<style>
  @media (max-width: 767.98px) {
    .chart-container {
      overflow-x: auto;
    }
    .chart-wrapper {
      min-width: 600px;
      height: 80vh;
    }
  }

  @media (min-width: 768px) {
    .chart-wrapper {
      width: 100%;
      height: 80vh;
    }
  }
</style>

<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
          <h3 class="mb-0">График доходов и расходов</h3>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addTransactionModal">Добавить</button>        </div>
      </div>
    </div>
  </div>
</div>

<!-- Модальное окно -->
<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('monthlyStats.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addTransactionModalLabel">Добавить транзакцию</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
        </div>
        <div class="modal-body">

        <div class="mb-3">
          <label class="form-label">Дата</label>
            <input type="date" name="date" class="form-control" value="{{ now()->format('Y-m-d') }}" max="{{ now()->format('Y-m-d') }}" required>
        </div>

          <div class="mb-3">
            <label class="form-label">Сумма в тг</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Тип</label>
            <select name="type_id" class="form-control" id="typeSelect" required>
              <option value="1">Доход</option>
              <option value="0">Расход</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Категория</label>
            <select name="category_id" class="form-control" id="categorySelect" required>
              {{-- опции будут добавляться через JS --}}
            </select>
          </div>

        <div class="mb-3">
          <label class="form-label">Счёт</label>
          <select name="account_id" class="form-control" required>
            @foreach($accounts as $account)
              <option value="{{ $account->id }}">{{ $account->name }}</option>
            @endforeach
          </select>
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



  <div class="container-fluid mb-3">
    <div class="row">
      <div class="col-12 d-flex justify-content-center align-items-center gap-3">
       <a href="{{ route('monthlyStats.index', ['month' => $prevMonth]) }}" class="btn btn-outline-secondary btn-sm">&larr;</a>
        <h4 class="mb-0">{{ $carbonMonth->translatedFormat('F Y') }}</h4>
        @if($carbonMonth->lt(now()->startOfMonth()))
          <a href="{{ route('monthlyStats.index', ['month' => $nextMonth]) }}" class="btn btn-outline-secondary btn-sm">&rarr;</a>
        @else
          <span class="btn btn-outline-secondary btn-sm disabled">&rarr;</span>
        @endif
      </div>
    </div>
  </div>

  <div class="container-fluid mb-4">
  <div class="row text-center">
    <div class="col-md-6">
      <div class="p-3 bg-light border rounded">
        <h6 class="text-success mb-1">Доход за месяц</h6>
        <div class="h5 mb-0">{{ number_format($totalIncome, 2, ',', ' ') }} $</div>
      </div>
    </div>
    <div class="col-md-6 mt-3 mt-md-0">
      <div class="p-3 bg-light border rounded">
        <h6 class="text-danger mb-1">Расход за месяц</h6>
        <div class="h5 mb-0">{{ number_format($totalExpense, 2, ',', ' ') }} $</div>
      </div>
    </div>
  </div>
</div>


<div class="app-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="chart-container">
          <div class="chart-wrapper">
            <canvas id="financeChart" style="width:100%; height:100%;"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection





@push('scripts')
<script>
const incomeCategories = @json($incomeCategories);
const expenseCategories = @json($expenseCategories);

const typeSelect = document.getElementById('typeSelect');
const categorySelect = document.getElementById('categorySelect');

function updateCategories(type) {
    categorySelect.innerHTML = '';
    const list = type == 1 ? incomeCategories : expenseCategories;

    list.forEach(cat => {
        const opt = document.createElement('option');
        opt.value = cat.id;
        opt.textContent = cat.name;
        categorySelect.appendChild(opt);
    });
}

typeSelect.addEventListener('change', e => {
    updateCategories(e.target.value);
});

updateCategories(typeSelect.value);
</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  // Приходят с контроллера
const labels = @json($dates);
const incomeData = @json($incomeData);
const expenseData = @json($expenseData);

const data = {
  labels: labels,
  datasets: [
    {
      label: 'Доходы',
      data: incomeData,
      borderColor: 'green',
      backgroundColor: 'rgba(9, 138, 37, 0.5)',
      fill: false
    },
    {
      label: 'Расходы',
      data: expenseData,
      borderColor: 'red',
      backgroundColor: 'rgba(255, 99, 132, 0.5)',
      borderDash: [5, 5],
      fill: false
    }
  ]
};

new Chart(document.getElementById('financeChart'), {
  type: 'line',
  data: data,
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
          display: false,
        }
      },
    scales: {
      x: {
        title: { display: true, text: 'Дата' }
      },
      y: {
        min: -500,
        max: 500,
        ticks: {
          stepSize: 100,
          callback: function(value) {
            return value.toLocaleString('ru-RU') + ' $';
          }
        },
        title: { display: true, text: 'Доллары' }
      }
    }
  }
});
</script>
@endpush



