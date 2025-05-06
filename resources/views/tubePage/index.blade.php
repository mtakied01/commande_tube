@extends('layouts.tube')

@section('title', 'Tube Area')
@section('dark', 'bg-black/60')

@section('secondaryMenu')
  <div
    class="bg-gray-900 text-white py-4 px-6 flex justify-center gap-8 text-lg font-semibold uppercase max-sm:flex-col max-sm:items-center">
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

  <div class="bg-black/5 px-5 pb-20 pt-10 rounded">
    <div class="absolute bg-neutral-300 -z-10 inset-0 bg-cover bg-center">
    </div>
    <h1 class="text-3xl px-2 py-6 rounded text-center uppercase font-bold text-black/60 b-4">Create new order :
    </h1>
    <form action="#" id="form" method="POST" class="space-y-4">
      @csrf
      <div id="tube-container">
        <div class="tube-group flex gap-2 items-end mb-2">
          <input id='input' type="text" placeholder="Scanner barcode ici"
            class="w-full py-3 my-2 px-10 placeholder-black text-center text-4xl bg-white/50">
        </div>
      </div>
    </form>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
      <table id="commande" class="min-w-full text-sm text-gray-800">
        <thead class="bg-gray-400 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
          <tr>
            <th class="px-4 py-3">APN</th>
            <th class="px-4 py-3">Quantity</th>
            <th class="px-4 py-3">action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200"></tbody>
      </table>
    </div>
    <div class="flex justify-center ">
      <button id="validation"
        class="bg-indigo-800 text-2xl uppercase text-amber-100 px-6 py-3 mt-5 hover:text-blue-500 hover:bg-white hover:font-bold cursor-pointer">Order</button>
    </div>

    @if (isset($data))
      {{ $data }}
    @endif
  </div>
@endsection
@section('script')
  <script>
    document.getElementById('form').addEventListener('keydown', (e) => {
      if (e.code == 'Enter') {
        e.preventDefault()
      }
    })

    const table1 = document.getElementById('commande');

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
    document.addEventListener('DOMContentLoaded', function() {
      const input = document.getElementById('input');

      input.addEventListener('keydown', (e) => {

        if (e.code == 'Enter') {
          e.preventDefault();

          let value = e.target.value.trim();
          e.target.value = ''
          if (value.toLowerCase().startsWith('1p')) {
            value = value.slice(2);
          }
          if (!value) return;

          // Check if already exists in the table
          const existingRows = Array.from(table1.querySelectorAll('tbody tr'));
          const alreadyExists = existingRows.some(row => row.children[0].textContent === value);

          if (alreadyExists) {
            alert('This APN has already been scanned.');
            e.target.value = '';
            return;
          }


          // async
          async function checkAndAddRow(value, key, table1, e) {
            try {
              const response = await fetch(`/api/check?value=${encodeURIComponent(value)}`, {
                method: 'GET'
              });
              const data = await response.json();

              if (data.exists) {
                const row = table1.getElementsByTagName('tbody')[0].insertRow(key);

                const col1 = row.insertCell(0);
                const col2 = row.insertCell(1);
                const col3 = row.insertCell(2);

                col1.textContent = value;
                col1.classList.add("px-4", "py-3", "text-3xl");

                const quantityWrapper = document.createElement('div');
                quantityWrapper.classList.add('flex', 'items-center', 'gap-2');

                for (let i = 1; i <= 8; i++) {
                  const button = document.createElement('button');
                  button.textContent = i;
                  button.classList.add('px-4', 'py-1', 'bg-gray-200', 'text-xl', 'rounded',
                  'hover:bg-gray-300');

                  button.addEventListener('click', (e) => {
                    quantityWrapper.querySelectorAll('button').forEach(btn => {
                      btn.classList.remove('border', 'border-2', 'border-indigo-500');
                    });
                    button.classList.add('border', 'border-2', 'border-indigo-500');
                    e.preventDefault();

                    let input = quantityWrapper.querySelector('input');
                    if (!input) {
                      input = document.createElement('input');
                      input.type = 'text';
                      input.value = i;
                      input.readOnly = true;
                      input.classList.add('hidden');
                      quantityWrapper.appendChild(input);
                    } else {
                      input.value = i;
                    }
                  });

                  quantityWrapper.appendChild(button);
                }

                col2.appendChild(quantityWrapper);
                e.target.value = '';

                const btn = document.createElement('button');
                btn.id = key;
                btn.textContent = '❌';
                btn.classList.add('px-3', 'py-2', 'cursor-pointer', 'text-xl');
                btn.addEventListener('click', (e) => {
                  deleteRow(e.target);
                  key--;
                  if (table1.querySelector('tbody').rows.length === 0) {
                    table1.classList.add('hidden');
                  } else {
                    table1.classList.remove('hidden');
                  }
                });

                col3.appendChild(btn);
                key++;

                if (table1.querySelector('tbody').rows.length === 0) {
                  table1.classList.add('hidden');
                } else {
                  table1.classList.remove('hidden');
                }

                e.preventDefault();
              }

            } catch (error) {
              console.error('Error fetching tube data:', error);
            }
          }


          checkAndAddRow(value, key, table1, e);


          // // // // // // // // // // 

        }

      });

    });
    // document.addEventListener('click',()=>console.log(table1.children[1].children));

    document.getElementById('validation').addEventListener('click', (e) => {

      if (e.code == 'keyA') {
        console.log('a');

      }

      e.preventDefault()

      const rows = table1.querySelector('tbody').rows;
      if (rows.length) {

        const data = []
        for (const row of rows) {
          data.push({
            dpn: row.children[0].textContent,
            qte: row.children[1].querySelector('input').value
          });
        }



        (async () => {
          try {
            const response = await fetch('/tube', {
              method: 'POST',
              headers: {
          'Content-type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              },
              body: JSON.stringify(data)
            });
            const result = await response.json();
            console.log('Réponse serveur:', result);
          } catch (error) {
            console.error(error);
          }
        })();



        table1.querySelector('tbody').innerHTML = ''
        key = 0;
      } else {
        alert('Pas de produit à commander')
      }
      if (table1.querySelector('tbody').rows.length == 0) {
        table1.classList.add('hidden')
      } else {
        table1.classList.remove('hidden')
      }
    })
    if (table1.querySelector('tbody').rows.length == 0) {
      table1.classList.add('hidden')
    } else {
      table1.classList.remove('hidden')
    }
  </script>
@endsection
