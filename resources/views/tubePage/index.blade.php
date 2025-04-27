@extends('layouts.tube')

@section('title', 'Tube Area')

@section('content')
  <h1 class="text-xl font-semibold mb-4">Créer une nouvelle commande</h1>
  <form action="#" method="POST" class="space-y-4">
    @csrf
    <div id="tube-container">
      <label class="block text-sm font-medium mb-2">Tubes</label>
      <div class="tube-group flex gap-2 items-end mb-2">
        <input id='input' type="text" placeholder="Scanner barcode ici"
          class="w-full py-3 my-2 px-10 text-center text-4xl">
      </div>
    </div>
  </form>

  <div class="overflow-x-auto bg-white shadow rounded-lg">
    <table id="commande" class="min-w-full text-sm text-gray-800">
      <thead class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
        <tr>
          <th class="px-4 py-3">DPN</th>
          <th class="px-4 py-3">Quantite</th>
          <th class="px-4 py-3">action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200"></tbody>
    </table>
  </div>
  <div id="validation" class="flex justify-center ">
    <button class="bg-indigo-800 text-amber-100 px-4 py-3 mt-5">Commander</button>
  </div>

  @if (isset($data))
    {{ $data }}
  @endif

@endsection
@section('script')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const input = document.getElementById('input');

      input.focus();

      document.addEventListener('click', (e) => {
        if (e.target !== input) {
          input.focus();
        }
      });

      document.addEventListener('keydown', (e) => {
        if (document.activeElement !== input) {
          input.focus();
        }
      });
    });


    function deleteRow(button) {
      button.closest('tr').remove();
    }

    let key = 0;
    const table1 = document.getElementById('commande');
    document.addEventListener('DOMContentLoaded', function() {
      const input = document.getElementById('input');

      input.addEventListener('input', (e) => {
        const value = e.target.value.trim();
        if (!value) return;

        const row = table1.getElementsByTagName('tbody')[0].insertRow(key);

        const col1 = row.insertCell(0);
        const col2 = row.insertCell(1);
        const col3 = row.insertCell(2);

        col1.textContent = value;
        col1.classList.add("px-4", "py-3", "text-3xl");


        const quantityWrapper = document.createElement('div');
        quantityWrapper.classList.add('flex', 'items-center', 'gap-2');

        const quantityInput = document.createElement('input');
        quantityInput.readOnly = true;
        quantityInput.value = 4;
        quantityInput.classList.add('px-2', 'border', 'text-3xl', 'text-center', 'w-20');


        const minusBtn = document.createElement('button');
        minusBtn.type = 'button';
        minusBtn.textContent = '➖';
        minusBtn.classList.add('px-2', 'py-1', 'bg-red-500', 'text-white', 'rounded');
        minusBtn.addEventListener('click', () => {
          if (quantityInput.value > 1) {
            quantityInput.value--;
          }
        });


        const plusBtn = document.createElement('button');
        plusBtn.type = 'button';
        plusBtn.textContent = '➕';
        plusBtn.classList.add('px-2', 'py-1', 'bg-green-500', 'text-white', 'rounded');
        plusBtn.addEventListener('click', () => {
          quantityInput.value++;
        });

        quantityWrapper.appendChild(minusBtn);
        quantityWrapper.appendChild(quantityInput);
        quantityWrapper.appendChild(plusBtn);

        col2.appendChild(quantityWrapper);


        e.target.value = '';

        const btn = document.createElement('button');
        btn.id = key;
        btn.textContent = '❌';
        btn.classList.add('px-3', 'py-2', 'cursor-pointer', 'text-xl')
        btn.addEventListener('click', (e) => {
          deleteRow(e.target);
          key--;
        });
        col3.appendChild(btn);
        key++;
      });

    });
    // document.addEventListener('click',()=>console.log(table1.children[1].children));

    document.getElementById('validation').addEventListener('click', () => {

      const rows = table1.querySelector('tbody').rows;
      if (rows.length) {

        const data = []
        for (const row of rows) {
          data.push({
            dpn: row.children[0].textContent,
            qte: row.children[1].querySelector('input').value
          });
        }



        fetch('/tube', {
            method: 'POST',
            headers: {
              'Content-type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
          })
          .then(res => res.json())
          .then(response => {
            console.log('Réponse serveur:', response);
          })
          .catch(error => {
            console.error('Erreur lors de l\'envoi:', error);
          });



        table1.querySelector('tbody').innerHTML = ''
        key = 0;
      } else {
        alert('Pas de produit à commander')
      }
    })
  </script>
@endsection
