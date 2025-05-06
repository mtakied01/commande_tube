@extends('layouts.admin')
@section('title', 'Racks')
@section('dark', 'bg-black/60')

@section('content')
  <div>
    {{-- <div class="overflow-hidden ">
      <div class="absolute inset-0 bg-cover bg-center"
        style="background-image: url('/aptiv2.jpg'); z-index: -1;">
      </div>
    </div> --}}

    <div class="max-w-7xl bg-black/30 mx-auto p-6">
      <h1 class="text-3xl text-white font-bold mb-6">Gestion des Produits</h1>

      <form action="{{ route('admin.searchRack') }}" method="post"
        class="space-y-4 p-6 bg-gray-400/20 shadow-md rounded-md">
        @csrf
        <div class="flex flex-col">
          <label class="uppercase text-sm font-bold text-gray-700 mb-2" for="apn">search</label>
          <div class="flex">
            <input type="text" id="apn" name="inpt"
              class="border uppercase border-black border-2 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input class="bg-blue-300 ml-2 p-2 px-6 rounded" type="submit" value="Search">
          </div>
        </div>
      </form>

      <div class="overflow-x-auto bg-gray-300 shadow rounded-lg">
        <table class="min-w-full text-sm text-gray-800">
          <thead class="bg-gray-100/85 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
            <tr>
              <th class="px-4 py-3">APN</th>
              <th class="px-4 py-3">Type</th>
              <th class="px-4 py-3">Packaging</th>
              <th class="px-4 py-3">Unity</th>
              <th class="px-4 py-3">Rack</th>
              <th class="px-4 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            @foreach ($products as $product)
              <tr class="cursor-pointer hover:bg-gray-100 transition" data-id="{{ $product->id }}"
                data-dpn="{{ $product->dpn }}" data-type="{{ $product->type }}" data-packaging="{{ $product->packaging }}"
                data-unity="{{ $product->unity }}" data-rack="{{ $product->rack }}">

                <td class="px-4 py-3 font-medium">{{ $product->dpn }}</td>
                <td class="px-4 py-3">{{ $product->type }}</td>
                <td class="px-4 py-3">{{ $product->packaging }}</td>
                <td class="px-4 py-3">{{ $product->unity }}</td>
                <form action="{{ route('admin.destroy', $product->id) }}" method="POST" class="inline-block ml-2">
                  <td class="px-4 py-3">{{ $product->rack }}</td>
                  <td class="px-4 py-3">
                    @csrf
                    @method('DELETE')
                    <input type="submit" class="text-red-600 hover:underline" value="Supprimer" />
                  </td>
                </form>
              </tr>
            @endforeach
          </tbody>
        </table>
        <div class="mt-4">
          {{ $products->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection


<div id="editModal" class="fixed inset-0 bg-black/50 hidden justify-center items-center z-50">
  <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
    <h2 class="text-xl font-bold mb-4">Update Product</h2>
    <form id="editForm" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" id="editId">

      <div class="mb-3">
        <label for="editDpn" class="block text-sm font-semibold">APN</label>
        <input type="text" id="editDpn" name="dpn" class="w-full border px-3 py-2 rounded">
      </div>
      <div class="mb-3">
        <label for="editType" class="block text-sm font-semibold">Type</label>
        <input type="text" id="editType" name="type" class="w-full border px-3 py-2 rounded">
      </div>
      <div class="mb-3">
        <label for="editPackaging" class="block text-sm font-semibold">Packaging</label>
        <input type="text" id="editPackaging" name="packaging" class="w-full border px-3 py-2 rounded">
      </div>
      <div class="mb-3">
        <label for="editUnity" class="block text-sm font-semibold">Unity</label>
        <input type="text" id="editUnity" name="unity" class="w-full border px-3 py-2 rounded">
      </div>
      <div class="mb-3">
        <label for="editRack" class="block text-sm font-semibold">Rack</label>
        <select name="rack" id="editRack" class="w-full border px-3 py-2 rounded">
          @foreach ($racks as $rack)
          <option value="{{ $rack }}">{{ $rack }}</option>
          @endforeach
        </select>
      </div>

      <div class="flex justify-end gap-2">
        <button type="button" id="closeModal" class="bg-gray-400 px-4 py-2 rounded">Back</button>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
      </div>
    </form>
  </div>
</div>



@section('script')
  <script>
    document.querySelectorAll('tbody tr').forEach(row => {
      row.addEventListener('click', (e) => {
        if (e.target.tagName.toLowerCase() === 'input' && e.target.type === 'submit') return;
        const modal = document.getElementById('editModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Remplir les champs
        document.getElementById('editId').value = row.dataset.id;
        document.getElementById('editDpn').value = row.dataset.dpn;
        document.getElementById('editType').value = row.dataset.type;
        document.getElementById('editPackaging').value = row.dataset.packaging;
        document.getElementById('editUnity').value = row.dataset.unity;
        document.getElementById('editRack').value = row.dataset.rack;

        // Met à jour l'action du formulaire
        document.getElementById('editForm').action = `/rack/update/${row.dataset.id}`;
      });
    });

    document.getElementById('closeModal').addEventListener('click', () => {
      const modal = document.getElementById('editModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    });
  </script>
@endsection
