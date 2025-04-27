@extends('layouts.main.app')
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('lg_menu')
  <li><a href="#" class="text-gray-700 hover:text-blue-500">Par scan</a></li>
  <li><a href="#" class="text-gray-700 hover:text-blue-500">Par machine</a></li>
@endsection
@section('sm_menu')
  <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Par scan</a>
  </li>
  <li><a href='#' class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Par machine</a>
  </li>
@endsection
