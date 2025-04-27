@extends('layouts.main.app')

@section('lg_menu')
  <li><a href="{{ route('admin.index') }}" class="text-gray-700 hover:text-blue-500">Produits</a></li>
  <li><a href="#" class="text-gray-700 hover:text-blue-500">Ajouter</a></li>
@endsection
@section('sm_menu')
  <li><a href="{{ route('admin.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Produits</a>
  </li>
  <li><a href="{{ route('admin.create') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Ajouter</a>
  </li>
@endsection
