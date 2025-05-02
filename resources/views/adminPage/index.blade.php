@extends('layouts.admin')
@section('title', 'Admin')
@section('dark','bg-black/60')  
@section('content')
  <div>
    {{-- <div class="overflow-hidden ">
      <div class="absolute inset-0 bg-cover bg-center"
        style="background-image: url('/aptiv2.jpg'); z-index: -1;">
      </div>
    </div> --}}

    <div class="max-w-7xl mx-auto p-6">
      <h1 class="text-3xl font-bold mb-6">Gestion des Produits</h1>

      <div class="overflow-x-auto bg-white/85 shadow rounded-lg">
        <table class="min-w-full text-sm text-gray-800">
          <thead class="bg-gray-100/85 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
            <tr>
              <th class="px-4 py-3">APN</th>
              <th class="px-4 py-3">Type</th>
              <th class="px-4 py-3">Packaging</th>
              <th class="px-4 py-3">Unité</th>
              <th class="px-4 py-3">Créé le</th>
              <th class="px-4 py-3">Modifié le</th>
              <th class="px-4 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            @foreach ($products as $product)
              <tr>
                <td class="px-4 py-3 font-medium">{{ $product->dpn }}</td>
                <td class="px-4 py-3">{{ $product->type }}</td>
                <td class="px-4 py-3">{{ $product->packaging }}</td>
                <td class="px-4 py-3">{{ $product->unity }}</td>
                <td class="px-4 py-3">{{ $product->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">{{ $product->updated_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                  <form action="{{ route('admin.destroy', $product->id) }}" method="POST" class="inline-block ml-2">
                    @csrf
                    @method('DELETE')
                    <input type="submit" class="text-red-600 hover:underline" value="Supprimer" />
                  </form>
                </td>
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
