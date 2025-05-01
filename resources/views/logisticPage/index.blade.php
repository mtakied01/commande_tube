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

  <div>
    <h1 class="text-xl font-semibold mb-4">Liste des Commandes</h1>
    <button id="openPopup" class="bg-blue-500 px-4 py-2 rounded">Commande</button>
  </div>

  {{-- Popup --}}
  <div id="popup" class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden z-50">
    <div class="bg-white p-6 rounded shadow-lg">
      <h2 class="text-lg font-semibold mb-4">Scanner la Commande</h2>
      <form id="popupForm" method="post">
        <label for="serialCmd" class="block mb-2">Serial de Commande:</label>
        <input type="text" id="serialCmd" name="serialCmd" class="w-full px-4 py-2 border rounded mb-4">
        <div class="flex justify-end">
          <button type="button" id="closePopup" class="bg-gray-500 px-4 py-2 rounded mr-2">Annuler</button>
          {{-- <button type="submit" class="bg-blue-500 px-4 py-2 rounded">Ajouter</button> --}}
        </div>
      </form>
    </div>
  </div>

  {{-- Search --}}
  <div class="flex justify-center items-center">
    <form class="w-full">
      <input type="text" placeholder="APN" id="input1"
        class="w-full py-3 my-2 px-4 sm:px-10 text-center text-xl sm:text-2xl md:text-3xl lg:text-4xl rounded bg-white/30">
      <input type="text" placeholder="Serial" id="serial"
        class="w-full py-3 my-2 px-4 sm:px-10 text-center text-xl sm:text-2xl md:text-3xl lg:text-4xl rounded bg-white/30">
      <input type="text" class="hidden" id="input2">
    </form>
  </div>

  {{-- Table --}}
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
        <tr>
          <td class="px-4 py-2">{!! App\Models\tube::find($order->tube_id)->dpn !!}</td>
          <td class="px-4 py-2">{{ $order->quantity }}</td>
          <td class="px-4 py-2">{!! App\Models\commande::find($order->serial_cmd)->user->matricule !!}</td>
          <td class="px-4 py-2">{!! App\Models\commande::find($order->serial_cmd)->barcode !!}</td>
          <td class="px-4 py-2">{{ $order->updated_at }}</td>
          <td class="px-4 py-2">{{ $order->statut }}</td>
          <td class="px-4 py-2">{{ $order->retard }}</td>
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

    <div class="mt-4 flex gap-4">
      <button id="exportExcel" class="bg-green-500 text-white px-4 py-2 rounded">Exporter en Excel</button>
      <button id="exportPdf" class="bg-red-500 text-white px-4 py-2 rounded">Exporter en PDF</button>
    </div>
  </div>
  <div id="form1"></div>
</div>
@endsection

@section('script')
<script>
  // Popup Handling
  const openPopup = document.getElementById('openPopup');
  const closePopup = document.getElementById('closePopup');
  const popup = document.getElementById('popup');
  const cmdInput = document.getElementById('serialCmd');

  openPopup.addEventListener('click', () => {
    popup.classList.remove('hidden');
    cmdInput.focus();
  });

  closePopup.addEventListener('click', () => {
    popup.classList.add('hidden');
  });

  cmdInput.addEventListener('keydown', (e) => {
    if (e.code == 'Enter') {
      e.preventDefault();
      const serialCmd = cmdInput.value;
      document.getElementById('input2').value = serialCmd;
      popup.classList.add('hidden');
    }
  });
</script>

{{-- <script>
  // Command Matching Logic
  const input1 = document.getElementById('input1');
  const input2 = document.getElementById('input2');
  const serials = @json($serials);

  input1.addEventListener('input', () => {
    if (serials.includes(input2.value.trim())) {
      fetch(`/logistic/${input1.value}`, {
        method: 'PUT',
        headers: {
          'Content-type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          serial_cmd: input2.value
        })
      })
      .then(res => res.json())
      .then(response => {
        console.log('Réponse serveur:', response);
      })
      .then(() => location.reload())
      .catch(error => {
        console.error(error);
      });
    }
  });
</script> --}}

{{-- validation --}}

<script>
  const scannedMap = new Map(); // Map<APN, Set<Serial>>
  let serialCmd = '';

  const apnInput = document.getElementById('input1');
  const serialProductInput = document.getElementById('serial');
  const hiddenSerialCmdInput = document.getElementById('input2');
  const scannedListContainer = document.createElement('div');
  scannedListContainer.className = "my-4 space-y-2";
  document.querySelector('#form1').appendChild(scannedListContainer);

  // Get cmdInput from previous scope if not already defined

  // const cmdInput = document.getElementById('serialCmd');
  // const popup = document.getElementById('popup');

  cmdInput.addEventListener('change', () => {
    serialCmd = cmdInput.value.trim();
    hiddenSerialCmdInput.value = serialCmd;
    popup.classList.add('hidden');
    apnInput.focus();
  });

  let currentAPN = '';

  apnInput.addEventListener('change', () => {
    currentAPN = apnInput.value.trim();
    serialProductInput.focus();
  });

  function renderScannedList() {
    scannedListContainer.innerHTML = '';
    scannedMap.forEach((serials, apn) => {
      const div = document.createElement('div');
      div.className = "p-2 bg-gray-100 rounded";

      div.innerHTML = `<strong>${apn}</strong>: ${Array.from(serials).join(', ')}`;
      scannedListContainer.appendChild(div);
    });
  }

  serialProductInput.addEventListener('change', () => {
    const serialProduct = serialProductInput.value.trim();

    if (!serialCmd || !currentAPN || !serialProduct) {
      alert("Tous les champs doivent être scannés.");
      return;
    }

    fetch('/check-product', {
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
    })
    .then(res => res.json())
    .then(data => {
      if (data.valid) {
        if (!scannedMap.has(currentAPN)) {
          scannedMap.set(currentAPN, new Set());
        }
        scannedMap.get(currentAPN).add(serialProduct);
        renderScannedList();
      } else {
        alert('Produit non valide ou déjà scanné');
      }

      apnInput.value = '';
      serialProductInput.value = '';
      currentAPN = '';
      apnInput.focus();
    })
    .catch(error => {
      console.error(error);
      alert('Erreur de vérification');
    });
  });

  const validateBtn = document.createElement('button');
  validateBtn.innerText = "✅ Valider la Commande";
  validateBtn.className = "bg-green-600 text-white px-4 py-2 rounded my-4";
  validateBtn.addEventListener('click', () => {
    if (!serialCmd || scannedMap.size === 0) {
      return alert("Aucun produit scanné.");
    }

    // Prepare the products list
    const products = [];
    scannedMap.forEach((serials, apn) => {
      products.push({
        apn,
        serials: Array.from(serials)
      });
    });

    fetch('/validate-products', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        serial_cmd: serialCmd,
        products
      })
    })
    .then(res => res.json())
    .then(response => {
      if (response.success) {
        alert("Validation réussie !");
        location.reload();
      } else {
        alert("Erreur lors de la validation.");
      }
    });
  });

  document.querySelector('#form1').appendChild(validateBtn);
</script>



@endsection
