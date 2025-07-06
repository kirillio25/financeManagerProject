@extends('layouts.cabinet_layout')

@section('title', 'Статистика за все время')

@section('content')

<style>
  @media (max-width: 767.98px) {
    .chart-container {
      overflow-x: auto;
    }
    .chart-wrapper {
      min-width: 800px;
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
      <div class="col-12 d-flex justify-content-between align-items-center">
        <h3 class="mb-0">График доходов и расходов по годам</h3>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid mb-4">
  <div class="row">
    <div class="col-12 d-flex justify-content-center align-items-center gap-3">
      <a href="{{ route('allTimeStats', ['start_year' => $startYear - 10]) }}" class="btn btn-outline-secondary btn-sm">&larr;</a>
      <h4 class="mb-0">{{ $startYear }} — {{ $endYear }}</h4>
      @if ($endYear < now()->year)
        <a href="{{ route('allTimeStats', ['start_year' => $startYear + 10]) }}" class="btn btn-outline-secondary btn-sm">&rarr;</a>
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
        <h6 class="text-success mb-1">Доход за период</h6>
        <div class="h5 mb-0">{{ number_format($totalIncome, 2, ',', ' ') }} $</div>
      </div>
    </div>
    <div class="col-md-6 mt-3 mt-md-0">
      <div class="p-3 bg-light border rounded">
        <h6 class="text-danger mb-1">Расход за период</h6>
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
            <canvas id="allTimeChart" style="width:100%; height:100%;"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('allTimeChart').getContext('2d');

const chartData = {
  labels: @json($years->pluck('year')),
  datasets: [
    {
      label: 'Доход',
      data: @json($years->pluck('income')),
      backgroundColor: 'rgba(25, 135, 84, 0.7)',
      borderColor: 'rgba(25, 135, 84, 1)',
      borderWidth: 1,
      categoryPercentage: 0.4,
      barPercentage: 0.8,
    },
    {
      label: 'Расход',
      data: @json($years->pluck('expense')),
      backgroundColor: 'rgba(220, 53, 69, 0.7)',
      borderColor: 'rgba(220, 53, 69, 1)',
      borderWidth: 1,
      categoryPercentage: 0.4,
      barPercentage: 0.8
    }
  ]
};

new Chart(ctx, {
  type: 'bar',
  data: chartData,
  options: {
    responsive: true,
    interaction: {
      mode: 'index',
      intersect: false
    },
    plugins: {
      legend: {
        display: false
      },
      title: {
        display: false
      }
    },
    scales: {
      y: {
        min: -500,
        max: 500,
        ticks: {
          stepSize: 100,
          callback: value => value + ' $'
        }
      }
    }
  }
});
</script>
@endpush
