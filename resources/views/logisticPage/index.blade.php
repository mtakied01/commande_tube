@extends('layouts.logistic')

@section('title', 'Consulter les Commandes')

@section('content')
  <h1 class="text-xl font-semibold mb-4">Liste des Commandes</h1>

  <div class="flex justify-center">
    <form>
      <input 
        type="text" 
        placeholder="DPN" 
        id="input1"
        class="py-3 my-2 px-10 text-center text-4xl"
      >
      <input 
        type="text" 
        placeholder="Serial number" 
        id="input2"
        class="py-3 my-2 px-10 text-center text-4xl"
      >
    </form>
  </div>

  <table class="min-w-full table-auto bg-white shadow-md rounded">
    <thead class="bg-gray-200">
      <tr>
        <th class="px-4 py-2 text-left">DPN</th>          {{-- pour confirmation --}}
        <th class="px-4 py-2 text-left">Quantité</th>
        <th class="px-4 py-2 text-left">Commandé par</th>
        <th class="px-4 py-2 text-left">serial de Commande</th>  {{-- pour confirmation --}}
        <th class="px-4 py-2 text-left">Date de Commande</th>
        <th class="px-4 py-2 text-left">Statut</th>
        <th class="px-4 py-2 text-left">Retard</th>
        <th class="px-4 py-2 text-left">Description</th>
        <th class="px-4 py-2 text-left">Rack</th>
        {{-- serial_cmd, tube_id, quantity, rack, statut, retard, description, updated_at --}} {{-- db structure --}}
      </tr>
    </thead>
    <tbody>
      @forelse ($orders as $order)
        <tr>
          <td class="px-4 py-2">{{ $order->tube->dpn }}</td> 
          <td class="px-4 py-2">{{ $order->quantity }}</td>
          <td class="px-4 py-2 bg-amber-500">{{ $order->commande->command_by }}</td>
          <td class="px-4 py-2">{{ $order->commande->barcode }}</td>
          <td class="px-4 py-2">{{ $order->updated_at }}</td>
          <td class="px-4 py-2">{{ $order->statut }}</td>
          <td class="px-4 py-2">{{ $order->retard }}</td>
          <td class="px-4 py-2">{{ $order->description }}</td>
          <td class="px-4 py-2 bg-amber-500"> {{ $order->rack }}</td> {{-- --}}
        </tr>
      @empty
      <tr>
        <td colspan="9" class="py-4 font-medium text-2xl tracking-wider text-center">Aucune commande</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-4">
    {{-- {{ $orders->links() }} --}}
  </div>
@endsection

@section('script')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const input1 = document.getElementById('input1');
    const input2 = document.getElementById('input2');
  
    input1.focus();
  
    input1.addEventListener('input', () => {
      if (input1.value.trim() !== '') {
        input2.focus();
      }
    });
  
    input2.addEventListener('input', () => {
      if (input2.value.trim() !== '') {
        console.log('Deuxième valeur scannée');
      }
    });
  });
  </script>
  
@endsection
