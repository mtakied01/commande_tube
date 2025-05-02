@extends('layouts.main.app')

@section('content')


  <div class="absolute inset-0  bg-cover bg-center"
    style="background-image: url('/aptiv1.jpg'); z-index: -1;">
  </div>

  @guest

    <div class="flex items-center justify-center mt-10">

      <div class="bg-white/80 backdrop-blur-md p-8 rounded-lg shadow-2xl fade-in delay-100 w-100">
        <h2 class="text-2xl font-bold text-center text-gray-700">Log In</h2>

        @if ($errors->any())
          <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            {{ $errors->first() }}
          </div>
        @endif

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
        </form>
      </div>

    </div>
  @endguest


@endsection
