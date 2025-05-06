@extends('layouts.admin')
@section('title', 'Historic Dashboard')
@section('jsExp')
  @vite(['resources/js/app.js'])
@endsection

@section('content')

  <h2>📊 Commandes vs Validations (Last 7 Days)</h2>

  <form action="{{ route('admin.history') }}" method="GET">
    <label for="selected_date">Select a Date:</label>
    <input type="date" id="selected_date" name="selected_date" value="{{ $selectedDate }}">
    <button type="submit">Filter</button>
  </form>

  <div class="chart-container">
    <canvas id="commandesChart" height="100"></canvas>
  </div>

@endsection

@section('script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const ctx = document.getElementById('commandesChart').getContext('2d');
      
      // Ensure chartData is passed correctly from Blade
      const chartData = {!! json_encode($chartData) !!};

      // Log chartData to check the structure
      console.log(chartData);

      // Prepare data
      const labels = chartData.map(item => item.date);  // Use date as x-axis labels
      const commandesData = {
        'Morning': chartData.map(item => item.commandes['Morning']),
        'Afternoon': chartData.map(item => item.commandes['Afternoon']),
        'Night': chartData.map(item => item.commandes['Night'])
      };
      const validatedData = {
        'Morning': chartData.map(item => item.validated['Morning']),
        'Afternoon': chartData.map(item => item.validated['Afternoon']),
        'Night': chartData.map(item => item.validated['Night'])
      };

      // Create the chart
      const chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,  // Dates as x-axis labels
          datasets: [
            {
              label: 'Morning Commandes',
              backgroundColor: '#3b82f6',
              data: commandesData['Morning'],
            },
            {
              label: 'Afternoon Commandes',
              backgroundColor: '#10b981',
              data: commandesData['Afternoon'],
            },
            {
              label: 'Night Commandes',
              backgroundColor: '#f59e0b',
              data: commandesData['Night'],
            },
            {
              label: 'Morning Validations',
              backgroundColor: '#34d399',
              data: validatedData['Morning'],
            },
            {
              label: 'Afternoon Validations',
              backgroundColor: '#60a5fa',
              data: validatedData['Afternoon'],
            },
            {
              label: 'Night Validations',
              backgroundColor: '#fbbf24',
              data: validatedData['Night'],
            }
          ]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false,
              position: 'top'
            },
            title: {
              display: true,
              text: 'Commandes vs Validations for Each Shift'
            }
          },
          scales: {
            x: {
              title: {
                display: true,
                text: 'Date'
              }
            },
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              }
            }
          }
        }
      });
    });
  </script>
@endsection
