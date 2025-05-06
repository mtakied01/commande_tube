@extends('layouts.admin')
@section('title', 'Admin')
@section('dark','bg-black/60')

@section('content')
  <form action="{{ route('apn.create') }}" method="post" class="space-y-4 p-6 bg-white/40 shadow-md rounded-md">
    @csrf
    <div class="flex flex-col">
      <label class="uppercase text-sm font-bold text-gray-700 mb-2" for="apn">apn</label>
      <input type="text" id="apn" name="dpn" class="border uppercase border-black rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="flex flex-col">
      <label class="uppercase text-sm font-bold text-gray-700 mb-2" for="type">type</label>
      <input type="text" id="type" name="type" class="border border-black rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="flex flex-col">
      <label class="uppercase text-sm font-bold text-gray-700 mb-2" for="packaging">packaging</label>
      <input type="text" id="packaging" name="packaging" class="border border-black rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="flex flex-col">
      <label class="uppercase text-sm font-bold text-gray-700 mb-2" for="unity">unity</label>
      <input type="text" id="unity" name="unity" class="border border-black rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="flex flex-col">
      <label class="uppercase text-sm font-bold text-gray-700 mb-2" for="unity">rack</label>
      <select id="rack" name="rack" class="border border-black rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        @foreach ($racks as $rack)
          <option value="{{ $rack }}">{{ $rack }}</option>
          @endforeach
      </select>
    </div>
    <input type="submit" value="ajouter" class="bg-blue-500 text-white uppercase font-bold py-2 px-4 rounded-md hover:bg-blue-600 cursor-pointer">
  </form>
@endsection