@extends('layouts.admin')
@section('title', 'Historic Dashboard')
@section('jsExp')
  @vite(['resources/js/app.js'])
@endsection

@section('content')

  <h2>Commandes vs Validations (Last 7 Days)</h2>

  <form action="{{ route('admin.history') }}" method="GET" class="flex items-end space-x-20 mr-10">
    <div class="w-full">
      <label class="block mb-2 text-sm font-medium text-gray-700" for="selected_date">Select a Date:</label>
      <input class="w-full px-4 py-2 border border-black rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        type="date" id="selected_date" name="selected_date" value="{{ $selectedDate }}">
    </div>
    <div
      class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <button type="submit">Filter</button>
    </div>
  </form>
  {{-- 
  <form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf

    <div>
      <label for="matricule" class="block mb-2 text-sm font-medium text-gray-700">Matricule</label>
      <input type="text" name="matricule" id="matricule" required
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div>
      <button type="submit"
        class="w-full px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        Connection
      </button>
    </div>
  </form> --}}

  <div class="chart-container">
    <canvas id="commandesChart" height="100"></canvas>
  </div>

@endsection

@section('script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const ctx = document.getElementById('commandesChart').getContext('2d');

      const chartData = {!! json_encode($chartData) !!};

      console.log(chartData);

      const labels = chartData.map(item => item.date);
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

      const chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
              label: 'Morning Commandes',
              backgroundColor: '#3b82f670',
              data: commandesData['Morning'],
            },
            {
              label: 'Afternoon Commandes',
              backgroundColor: '#3b82f670',
              data: commandesData['Afternoon'],
            },
            {
              label: 'Night Commandes',
              backgroundColor: '#3b82f670',
              data: commandesData['Night'],
            },
            {
              label: 'Morning Validations',
              backgroundColor: '#34d39970',
              data: validatedData['Morning'],
            },
            {
              label: 'Afternoon Validations',
              backgroundColor: '#34d39970',
              data: validatedData['Afternoon'],
            },
            {
              label: 'Night Validations',
              backgroundColor: '#34d39970',
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
