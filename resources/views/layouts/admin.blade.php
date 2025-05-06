@extends('layouts.main.app')

@section('dark','bg-black/60')

@section('secondaryMenu')
<div
    class="bg-gray-900 text-white py-4 px-6 rounded-t-md flex justify-center gap-8 text-lg font-semibold uppercase max-sm:flex-col max-sm:items-center">
    <a href="{{ route('admin.apn') }}"
      class="hover:text-amber-400 {{ request()->routeIs('admin.apn') ? 'text-amber-400 underline' : '' }}">
      APN
    </a>
    <a href="{{ route('admin.rack') }}"
      class="hover:text-amber-400 {{ request()->routeIs('admin.rack') ? 'text-amber-400 underline' : '' }}">
      RACK
    </a>
    <a href="{{ route('admin.history') }}"
      class="hover:text-amber-400 {{ request()->routeIs('admin.history') ? 'text-amber-400 underline' : '' }}">
      History
    </a>
  </div>
  <div class="absolute bg-neutral-300 -z-10 inset-0 bg-cover bg-center">
  </div>
@endsection