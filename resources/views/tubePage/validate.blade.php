@extends('layouts.tube')

@section('title', 'Valider les Commandes')
@section('dark', 'bg-black/60')

@section('header', '')

@section('secondaryMenu')
  <div
    class="bg-gray-900 text-white py-4 px-6 rounded-t-md flex justify-center gap-8 text-lg font-semibold uppercase max-sm:flex-col max-sm:items-center">
    <a href="{{ route('tube.index') }}"
      class="hover:text-amber-400 {{ request()->routeIs('tube.index') ? 'text-amber-400 underline' : '' }}">
      New Order
    </a>
    <a href="{{ route('tube.create') }}"
      class="hover:text-amber-400 {{ request()->routeIs('tube.create') ? 'text-amber-400 underline' : '' }}">
      Confirm order
    </a>
  </div>
@endsection

@section('content')


  <div class="text-black bg-black/5 p-4">
    <div class="absolute inset-0 bg-cover bg-center bg-neutral-300 -z-10"></div>

    <div class="my-4">
      <h1 class="text-xl font-semibold mb-4">Order list</h1>
      <button id="openPopup" class="bg-blue-500 px-4 py-2 rounded text-white font-semibold">Scan Order</button>
    </div>

    <div id="popup" class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden z-50">
      <div class="bg-white p-6 rounded shadow-lg">
        <h2 class="text-lg font-semibold mb-4">Scanner la Commande</h2>
        <input type="text" id="serialCmd" placeholder="Serial de Commande"
          class="w-full px-4 py-2 border rounded mb-4">
        <div class="flex justify-end">
          <button type="button" id="closePopup" class="bg-gray-500 text-white px-4 py-2 rounded">Annuler</button>
        </div>
      </div>
    </div>

    <div class="flex flex-col items-center">
      <input type="text" placeholder="APN" id="input1"
        class="w-full border border-black placeholder-black max-w-3xl py-3 my-2 px-6 text-center text-2xl rounded bg-white/30">
      <input type="text" placeholder="Serial produit" id="serial"
        class="w-full border border-black placeholder-black max-w-3xl py-3 my-2 px-6 text-center text-2xl rounded bg-white/30">
    </div>

    <div class="overflow-x-auto z-30 mt-6">
      <table class="min-w-full table-auto bg-white shadow-md rounded text-sm sm:text-base">
        <thead class="bg-gray-200">
          <tr>
            <th class="px-4 py-2 text-left">APN</th>
            <th class="px-4 py-2 text-left">Quantity</th>
            <th class="px-4 py-2 text-left">Ordered by</th>
            <th class="px-4 py-2 text-left">Serial Commande</th>
            <th class="px-4 py-2 text-left">Date</th>
            <th class="px-4 py-2 text-left">Status</th>
            <th class="px-4 py-2 text-left">Retard</th>
            <th class="px-4 py-2 text-left">Description</th>
            <th class="px-4 py-2 text-left">Rack</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($orders as $order)
            @php
              $hours = intval(Carbon\Carbon::parse($order->created_at)->diffInHours(now(), true));
            @endphp
            <tr class="{{ $hours >= 2 ? 'bg-red-700' : '' }}">
              <td class="px-4 py-2">{!! App\Models\tube::find($order->tube_id)->dpn !!}</td>
              <td class="px-4 py-2">{{ $order->quantity }}</td>
              <td class="px-4 py-2">{!! App\Models\commande::find($order->serial_cmd)->user->matricule !!}</td>
              <td class="px-4 py-2">{!! App\Models\commande::find($order->serial_cmd)->barcode !!}</td>
              <td class="px-4 py-2">{{ $order->created_at->format('Y/m/d H:i') }}</td>
              <td class="px-4 py-2">{{ $order->statut }}</td>
              <td class="px-4 py-2">
                {{ intval(Carbon\Carbon::parse($order->created_at)->diffInHours(now(), true)) }} h
                {{ Carbon\Carbon::parse($order->created_at)->diff(now())->format('%I') }} min
              </td>
              <td class="px-4 py-2">{{ $order->description }}</td>
              <td class="px-4 py-2"> {{ $order->rack }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="py-4 font-medium text-xl text-center">Aucune commande</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="flex flex-col items-center mt-6 space-y-2">
      <button id="confirmBtn"
        class="bg-green-600 text-white font-semibold px-6 py-3 rounded mt-6 hover:bg-white hover:text-green-600 cursor-pointer">✅
        Confirm Order</button>
      <div id="scannedList"></div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    const openPopup = document.getElementById('openPopup');
    const closePopup = document.getElementById('closePopup');
    const popup = document.getElementById('popup');
    const cmdInput = document.getElementById('serialCmd');
    const apnInput = document.getElementById('input1');
    const serialInput = document.getElementById('serial');
    const scannedList = document.getElementById('scannedList');

    let serialCmd = '';
    let currentAPN = '';
    const scannedApnSeries = new Map();

    openPopup.onclick = () => {
      popup.classList.remove('hidden');
      cmdInput.focus();
    };

    closePopup.onclick = () => popup.classList.add('hidden');

    cmdInput.addEventListener('keydown', (e) => {
      if (e.code === 'Enter') {
        e.preventDefault();
        serialCmd = cmdInput.value.trim();
        popup.classList.add('hidden');
        apnInput.focus();
      }
    });

    apnInput.addEventListener('keydown', (e) => {
      if (e.code === 'Enter') {
        if (apnInput.value.trim().startsWith('1P')) {
          currentAPN = apnInput.value.trim().slice(2);
        }else{
          currentAPN = apnInput.value.trim();
        }
        serialInput.focus();
      }
    });

    serialInput.addEventListener('keydown', (e) => {
      if (e.code === 'Enter') {
        const serialProduct = serialInput.value.trim();
        if (!serialCmd || !currentAPN || !serialProduct) {
          alert("Tous les champs doivent être scannés.");
          return;
        }

        (async () => {
          try {
            const response = await fetch('/api/check-product', {
              method: 'POST',
              headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              },
              body: JSON.stringify({
          serial_cmd: serialCmd,
          apn: currentAPN,
          serial_product: serialProduct
              })
            });

            const data = await response.json();

            if (data.valid) {
              if (!scannedApnSeries.has(currentAPN)) scannedApnSeries.set(currentAPN, new Set());
              scannedApnSeries.get(currentAPN).add(serialProduct);
              renderScannedList();
            } else {
              alert('Produit non valide ou déjà scanné');
            }

            apnInput.value = '';
            serialInput.value = '';
            currentAPN = '';
            apnInput.focus();
          } catch (error) {
            alert('Erreur de vérification');
          }
        })();
      }
    });

    function renderScannedList() {
      scannedList.innerHTML = '';
      scannedApnSeries.forEach((serials, apn) => {
        const div = document.createElement('div');
        div.className = "p-2 bg-gray-100 rounded w-full max-w-3xl text-center";
        div.innerHTML = `<strong>${apn}</strong>: <span class="text-gray-700">(${serials.size} produits scannés)</span>`;
        scannedList.appendChild(div);
      });
    }

    const validateBtn = document.getElementById('confirmBtn');
    validateBtn.onclick = () => {
      if (!serialCmd || scannedApnSeries.size === 0) return alert("Aucun produit scanné.");

      const products = Array.from(scannedApnSeries.entries()).map(([apn, serials]) => ({
        apn,
        serials: Array.from(serials)
      }));

      (async () => {
        try {
          const response = await fetch('/validate-products', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          serial_cmd: serialCmd,
          products
        })
          });

          const data = await response.json();

          if (data.success) {
        alert("Validation réussie !");
        location.reload();
          } else {
        alert("Erreur lors de la validation.");
          }
        } catch (error) {
          alert("Une erreur s'est produite lors de la validation.");
        }
      })();
    };

    // scannedList.appendChild(validateBtn);
  </script>
@endsection
