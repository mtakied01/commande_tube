@extends('layouts.main.app')
@section('meta')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

{{-- @section('lg_menu')
  <li><a href="#" class="hover:text-blue-500">Commander</a></li>
@endsection
@section('sm_menu')
  <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Commander</a>
  </li>
@endsection --}}
