@extends('layouts.admin')
@section('title', 'Historic Dashboard')

@section('content')
  <h1 class="text-3xl font-bold mb-6">Dashboard: Tube Orders by Shift and Day of the Week</h1>
  <div class="container flex w-full justify-between mx-auto p-6 space-x-4">
    <div class="mb-8 w-full">
      <h2 class="text-xl font-semibold">Orders by Day of the Week</h2>
      <canvas id="statusChart"></canvas>
    </div>
  </div>
@endsection

@section('script')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const labels = {!! json_encode($chartLabels) !!};
    const datasets = {!! json_encode($chartData) !!};

    const ctx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: datasets
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: 'État des commandes par jour et shift'
          },
          tooltip: {
            mode: 'nearest',
            intersect: true,
            callbacks: {
              label: function(tooltipItem) {
                return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
              }
            }
          },
          legend: {
            display: false,
            position: 'top'
          }
        },
        interaction: {
          mode: 'nearest',
          intersect: true
        },
        scales: {
          x: {
            stacked: true,
            title: {
              display: true,
              text: 'Jours (répétés par shift)'
            }
          },
          y: {
            stacked: true,
            title: {
              display: true,
              text: 'Quantité'
            },
            beginAtZero: true
          }
        }
      }
    });
  </script>
@endsection
