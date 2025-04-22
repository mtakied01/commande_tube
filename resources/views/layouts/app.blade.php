<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin')</title>
  @vite('resources/css/app.css')
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">

  <!-- Navbar -->
  <nav class="bg-white border-b shadow-md p-4" x-data="{ open: false }">
    <div class="container mx-auto flex justify-between items-center">
      <a href="{{ route('products.index') }}" class="text-lg font-bold text-blue-600 tracking-widest">APTIV</a>

      <!-- Hamburger button -->
      <button @click="open = !open" class="sm:hidden text-gray-700 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path :class="{ 'hidden': open, 'inline-flex': !open }" stroke-linecap="round" stroke-linejoin="round"
            stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          <path :class="{ 'inline-flex': open, 'hidden': !open }" stroke-linecap="round" stroke-linejoin="round"
            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <ul class="hidden sm:flex gap-6 text-sm sm:text-base">
        <li><a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-500">Produits</a></li>
        <li><a href="{{ route('products.create') }}" class="text-gray-700 hover:text-blue-500">Ajouter</a></li>
        <li><a href="#" class="text-red-500 hover:text-red-700">Déconnexion</a></li>

      </ul>
    </div>

    <div class="sm:hidden mt-2" x-show="open" @click.away="open = false">
      <ul class="flex flex-col gap-2">
        <li><a href="{{ route('products.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Produits</a>
        </li>
        <li><a href="{{ route('products.create') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Ajouter</a>
        </li>
        <li><a href="#" class="text-red-500 block px-4 py-2 hover:text-red-700">Déconnexion</a></li>

      </ul>
    </div>
  </nav>

  <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 flex-grow">
    @yield('content')
  </main>

  <footer class="bg-white text-center text-xs text-gray-500 py-4 border-t">
    &copy; {{ date('Y') }} Aptiv - Tous droits réservés.
  </footer>

</body>

</html>
