@extends('layouts.main.app')


@section('content')
  <div class="absolute inset-0  bg-cover bg-center"
    style="background-image: url('/aptiv1.jpg'); z-index: -1;">
  </div>
  <div class="min-h-[140px] flex items-center justify-center bg-cover bg-center z-50">

    <ul class="flex justify-center gap-10 text-sm sm:text-base">
      <li class="text-amber-600 bg-gray-200 rounded px-5 py-2 text-2xl">
        <a href="/tube">Envoyer une Commande</a>
      </li>
      {{-- <li class="text-amber-600 bg-gray-200 rounded px-5 py-2 text-2xl">
        <a href="/tube">Tube Area</a>
      </li>
      <li class="text-amber-600 bg-gray-200 rounded px-5 py-2 text-2xl">
        <a href="/creamping">Soldering Area</a>
      </li> --}}
    </ul>
  </div>
@endsection
