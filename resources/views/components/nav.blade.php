   <nav class="bg-gray-800 text-white px-6 py-4">
       <div class="container mx-auto flex items-center justify-between relative">
           <a class="text-2xl font-bold mr-8 tracking-wider" href="/">Чтеније</a>

           <!-- Toggler for mobile view -->
           <button
               class="lg:hidden px-4 py-2 border border-transparent rounded-md text-white hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white z-10"
               type="button" id="navbar-toggler">
               <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                   xmlns="http://www.w3.org/2000/svg">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                   </path>
               </svg>
           </button>

           <!-- Navbar items for larger screens -->
           <div id="navbar-menu"
               class="hidden lg:flex lg:items-center lg:space-x-6 w-full flex-col lg:flex-row transition-all duration-300 ease-in-out transform lg:transform-none">
               <div
                   class="flex-grow flex flex-col lg:flex-row items-center space-x-0 lg:space-x-6 space-y-4 lg:space-y-0">
                   <ul class="flex flex-col lg:flex-row items-center space-y-4 lg:space-y-0 lg:space-x-6">

                       <li class="relative flex items-center">
                           <x-dropdown align="right" width="48">
                               <x-slot name="trigger">
                                   <button
                                       class="inline-flex items-center px-3 py-4 border border-transparent text-lg leading-4 rounded-md hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                       <div>{{ __('Knjige') }}</div>

                                       <div class="ms-1 text-xs">
                                           <i class="fa-solid fa-chevron-down"></i>
                                       </div>
                                   </button>
                               </x-slot>
                               {{-- Navbar links for books view --}}
                               <x-slot name="content">
                                   <x-dropdown-link href="/knjige">
                                       {{ __('Sve') }}
                                   </x-dropdown-link>
                                   @foreach ($vrsteArtikala as $vrsta)
                                       <x-dropdown-link href="/knjige/{{ $vrsta->id }}">
                                           {{ $vrsta->naziv }}
                                       </x-dropdown-link>
                                   @endforeach
                               </x-slot>
                           </x-dropdown>
                       </li>
                       <li class="relative flex items-center"><a class="text-lg hover:text-gray-300" href="#">
                               {{ __('O nama') }}</a></li>
                       <li class="relative flex items-center"><a class="text-lg hover:text-gray-300"
                               href="../admin/admin.php">{{ __('Admin') }}</a></li>
                   </ul>
               </div>

               <div class="flex flex-col lg:flex-row items-center space-y-4 lg:space-y-0 lg:space-x-4 mt-3 lg:mt-0">
                   <form action="" method="post"
                       class="flex flex-col lg:flex-row items-center space-y-4 lg:space-y-0 lg:space-x-2">
                       <div>
                           <input class="form-input px-4 py-2 rounded-lg border-gray-300 text-lg" type="text"
                               placeholder="Pojam za pretragu" aria-label="Search" />
                           <button type="submit"
                               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 text-lg">
                               <i class="fa-solid fa-magnifying-glass"></i>
                           </button>
                       </div>
                   </form>

                   @guest
                       <ul class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-6">
                           <li><a class="text-lg hover:text-gray-300" href="/login">{{ __('Prijava') }}</a></li>
                           <li><a class="text-lg hover:text-gray-300" href="register">{{ __('Registracija') }}</a></li>
                           <li class="relative">
                               <button class="text-xl text-white hover:text-gray-300" id="user-menu-toggle">
                                   <!-- User icon here if any -->
                               </button>
                               <div class="absolute hidden mt-2 w-56 bg-white text-black shadow-lg rounded-lg"
                                   id="user-menu">
                                   <a class="block px-4 py-3 text-base hover:bg-gray-100"
                                       href="../nalog.php">{{ __('Profil') }}</a>
                                   <a class="block px-4 py-3 text-base hover:bg-gray-100"
                                       href="logout">{{ __('Odjava') }}</a>
                               </div>
                           </li>
                       </ul>
                   @endguest

                   @auth
                       <x-dropdown align="right" width="48">
                           <x-slot name="trigger">
                               <button
                                   class="inline-flex items-center px-6 py-4 border border-transparent text-lg leading-4 rounded-md hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                   <div>{{ Auth::user()->ime }}</div>

                                   <div class="ms-1 text-xs">
                                       <i class="fa-solid fa-chevron-down"></i>
                                   </div>
                               </button>
                           </x-slot>

                           <x-slot name="content">
                               <x-dropdown-link :href="route('profile.edit')">
                                   {{ __('Profil') }}
                               </x-dropdown-link>

                               <!-- Authentication -->
                               <form method="POST" action="{{ route('logout') }}">
                                   @csrf

                                   <x-dropdown-link :href="route('logout')"
                                       onclick="event.preventDefault();
                                         this.closest('form').submit();">
                                       {{ __('Odjava') }}
                                   </x-dropdown-link>
                               </form>
                           </x-slot>
                       </x-dropdown>
                   @endauth

                   <!-- Cart Icon -->
                   <a href="../cart.php"
                       class="bg-yellow-500 text-white px-5 py-3 rounded-lg flex items-center text-lg">
                       <i class="fa-solid fa-cart-shopping text-xl"></i>
                       <span id="cart-count" class="ml-2">0</span>
                   </a>
               </div>
           </div>
       </div>
   </nav>

   <!-- JavaScript for dropdown functionality -->
   <script>
       document.addEventListener('DOMContentLoaded', function() {
           const navbarToggler = document.getElementById('navbar-toggler');
           const navbarMenu = document.getElementById('navbar-menu');

           // Toggle for mobile menu
           navbarToggler.addEventListener('click', function() {
               navbarMenu.classList.toggle('hidden');
           });
       });
   </script>
