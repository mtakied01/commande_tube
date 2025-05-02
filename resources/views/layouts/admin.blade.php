@extends('layouts.main.app')

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
    <a href="{{ route('admin.rack') }}"
      class="hover:text-amber-400 {{ request()->routeIs('admin.rack') ? 'text-amber-400 underline' : '' }}">
      History
    </a>
  </div>
@endsection