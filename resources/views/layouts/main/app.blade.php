<!DOCTYPE html>
<html>
{{-- lang="{{ str_replace('_', '-', app()->getLocale()) }}" --}}

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @yield('meta')
  <title>@yield('title', 'Admin')</title>
  @vite(['resources/css/app.css'])
  @yield('jsExp')
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script defer>
    document.addEventListener("DOMContentLoaded", () => {
      const elements = document.querySelectorAll(".scroll-text");
      const speed = .5;
      const spacing = 100;

      elements.forEach((el, i) => {
        el.dataset.x = i * (el.offsetWidth + spacing);
        el.style.transform = `translateX(${el.dataset.x}px)`;
      });

      function animate() {
        elements.forEach(el => {
          let x = parseFloat(el.dataset.x);
          x -= speed;

          if (x + el.offsetWidth < 0) {
            const maxX = Math.max(...Array.from(elements).map(e => parseFloat(e.dataset.x)));
            x = maxX + el.offsetWidth + spacing;
          }

          el.dataset.x = x;
          el.style.transform = `translateX(${x}px)`;
        });

        requestAnimationFrame(animate);
      }

      requestAnimationFrame(animate);
    });
  </script>



</head>


<style>
  .scroll-wrapper {
    overflow: hidden;
    white-space: nowrap;
    position: relative;
  }


  .scroll-text {
    padding-right: 6rem;
    font-size: 6rem;
    font-weight: 800;
    font-family: "Cascadia Code", monospace;
  }


  .fade-in {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 1s ease-out forwards;
  }

  .outlined-text {
    text-shadow:
      -3px -3px 0 #000,
      3px -3px 0 #000,
      -3px 3px 0 #000,
      3px 3px 0 #000;
  }


  @keyframes fadeInUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>



<body class="relative  text-gray-800 min-h-screen flex flex-col">

  {{-- <div class="absolute inset-0 bg-black/30  -z-50"></div> --}}

  <div class="relative z-10 flex flex-col min-h-screen">

    <nav class="@yield('dark', '') backdrop-blur-sm border-b shadow-md p-4 text-white" x-data="{ open: false }">
      <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center">
          <span class="w-4 h-4 bg-[#f84018] rounded-full"></span>
          <a href="{{ route('home') }}" class="text-6xl max-sm:text-lg font-extrabold tracking-widest">APTIV</a>
          <span class="w-4 h-4 bg-[#f84018] rounded-full"></span>
        </div>

        <button @click="open = !open" class="sm:hidden text-gray-700 focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path :class="{ 'hidden': open, 'inline-flex': !open }" stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{ 'inline-flex': open, 'hidden': !open }" stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <ul class="hidden sm:flex gap-6 text-sm sm:text-base">
          @yield('lg_menu')
          <li>
            <a href="{{ route('tube.index') }}">Tube</a>
          </li>
          <li>
            <a href="{{ route('logistic.index') }}">Logistic</a>
          </li>
          @auth
            <li>
              <form method="POST" action="{{ route('logout') }}" class="text-red-500 hover:text-red-700">
                @csrf
                <button type="submit" class="text-red-500 hover:underline">Déconnexion</button>
              </form>
            </li>
          </ul>
        @endauth
      </div>

      <div class="sm:hidden mt-2" x-show="open" @click.away="open = false">
        <ul class="flex flex-col gap-2">
          @yield('sm_menu')
          <li class="block px-4 py-2">
            <a href="{{ route('tube.index') }}">Tube</a>
          </li>
          <li class="block px-4 py-2">
            <a href="{{ route('logistic.index') }}">Logistic</a>
          </li>
          <li>
            <form method="POST" action="{{ route('logout') }}" class="text-red-500 block px-4 py-2 hover:text-red-700">
              @csrf
              <button type="submit" class="text-red-500 hover:underline">Déconnexion</button>
            </form>
          </li>
        </ul>
      </div>
    </nav>
    @yield('secondaryMenu')

    @section('header')
      <div class="scroll-wrapper w-full h-32 overflow-hidden relative flex gap-4 items-center my-10">
        <div class="scroll-text text-orange-300 text-6xl font-bold outlined-text absolute cursor-default"
          style="font-family: 'Cascadia Code', monospace;">RM ORDERING OF LEAD PREP AREA</div>
        <div class="scroll-text text-orange-300 text-6xl font-bold outlined-text absolute"
          style="font-family: 'Cascadia Code', monospace;">RM ORDERING OF LEAD PREP AREA</div>
      </div>
    @show


    <main class="container mx-auto flex-grow px-4 sm:px-6 lg:px-1 py-8">
      @yield('content')
    </main>

    <footer class="bg-white/10 backdrop-blur-sm text-center text-xs text-white py-4 border-t z-50">
      &copy; {{ date('Y') }} Aptiv - Tous droits réservés.
    </footer>

  </div>

</body>


@yield('script')

</html>
