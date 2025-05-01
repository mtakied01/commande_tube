@extends('layouts.logistic')

@section('jsExp')
@vite(['resources/js/app.js'])
@endsection

@section('title', 'Consulter les Commandes')
@section('dark', 'bg-black/60')

@section('header', '')

@section('content')
<div class="text-black">
  <div class="absolute inset-0 bg-cover bg-center bg-amber-700/50 -z-10"></div>

  <div class="flex justify-between  mb-5">
    <h1 class="text-xl font-semibold flex items-center">Liste des Commandes</h1>
    <div class="mt-4 flex gap-4">
      <button id="exportExcel" class="bg-green-500 text-white px-4 py-2 rounded">Exporter en Excel</button>
      <button id="exportPdf" class="bg-red-500 text-white px-4 py-2 rounded">Exporter en PDF</button>
    </div>
  </div>


  <div class="overflow-x-auto z-30">
    <table id="ordersTable" class="min-w-full table-auto bg-white shadow-md rounded text-sm sm:text-base">
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2 text-left">APN</th>
          <th class="px-4 py-2 text-left">Quantité</th>
          <th class="px-4 py-2 text-left">Commandé par</th>
          <th class="px-4 py-2 text-left">Serial de Commande</th>
          <th class="px-4 py-2 text-left">Date de Commande</th>
          <th class="px-4 py-2 text-left">Statut</th>
          <th class="px-4 py-2 text-left">Retard</th>
          <th class="px-4 py-2 text-left">Description</th>
          <th class="px-4 py-2 text-left">Rack</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($orders as $order)
            @php
              $hours = floor($order->retard / 3600);
              $minutes = floor(($order->retard % 3600) / 60);
            @endphp
        <tr class="{{ $hours ? 'bg-red-700' : '' }}">
          <td class="px-4 py-2">{!! App\Models\tube::find($order->tube_id)->dpn !!}</td>
          <td class="px-4 py-2">{{ $order->quantity }}</td>
          <td class="px-4 py-2">{!! App\Models\commande::find($order->serial_cmd)->user->matricule !!}</td>
          <td class="px-4 py-2">{!! App\Models\commande::find($order->serial_cmd)->barcode !!}</td>
          <td class="px-4 py-2">{{ $order->created_at }}</td>
          <td class="px-4 py-2">{{ $order->statut }}</td>
            <td class="px-4 py-2">
            {{ $hours }}h {{ $minutes }}m
            </td>
          <td class="px-4 py-2">{{ $order->description }}</td>
          <td class="px-4 py-2"> {{ $order->rack }}</td>
        </tr>
        @empty
          <tr>
            <td colspan="9" class="py-4 font-medium text-2xl text-center">Aucune commande</td>
          </tr>
        @endforelse
      </tbody>
    </table>


  </div>
</div>
@endsection

