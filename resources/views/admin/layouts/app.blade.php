<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    
    @stack('head')
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <nav class="bg-[#FBE9EB] px-6 py-4 shadow font-montserrat">
        <div class="max-w-7xl mx-auto flex justify-between items-center">

            {{-- Logo --}}
            <a href="/">
                <img src="{{ asset('storage/images/ansthelabel.png') }}" class="h-10" alt="AnstheLabel Logo">
            </a>

            {{-- Menu Desktop --}}
            <div class="hidden md:flex items-center space-x-[50px]">
                <a href="/produk"
                    class="{{ request()->is('produk*') ? 'font-bold' : '' }} text-black hover:font-bold">Produk</a>
                <a href="/kategori"
                    class="{{ request()->is('kategori*') ? 'font-bold' : '' }} text-black hover:font-bold">Kategori</a>
                <a href="/ukuran"
                    class="{{ request()->is('ukuran*') ? 'font-bold' : '' }} text-black hover:font-bold">Ukuran</a>
                <a href="/warna"
                    class="{{ request()->is('warna*') ? 'font-bold' : '' }} text-black hover:font-bold">Warna</a>
                <a href="/transaksi"
                    class="{{ request()->is('transaksi*') ? 'font-bold' : '' }} text-black hover:font-bold">Transaksi</a>
            </div>

            {{-- Dropdown User (Desktop) --}}
            <div class="hidden md:block relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 hover:text-blue-600"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A8.966 8.966 0 0012 21c2.485 0 4.735-.998 6.364-2.617M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg py-1 z-50">
                    <a href="{{ url('/user/change-password') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Ganti
                        Password</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>

            {{-- Hamburger Menu (Mobile) --}}
            <div class="block md:hidden" x-data="{ open: false }">
                <button @click="open = !open">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                {{-- Menu Mobile --}}
                <div x-show="open"
                    class="absolute top-16 left-0 w-full bg-pink-200 shadow-md flex flex-col space-y-4 p-4">
                    <a href="/produk"
                        class="{{ request()->is('produk*') ? 'font-bold' : '' }} text-black hover:font-bold">Produk</a>
                    <a href="/kategori"
                        class="{{ request()->is('kategori*') ? 'font-bold' : '' }} text-black hover:font-bold">Kategori</a>
                    <a href="/ukuran"
                        class="{{ request()->is('ukuran*') ? 'font-bold' : '' }} text-black hover:font-bold">Ukuran</a>
                    <a href="/warna"
                        class="{{ request()->is('warna*') ? 'font-bold' : '' }} text-black hover:font-bold">Warna</a>
                    <a href="/transaksi"
                        class="{{ request()->is('transaksi*') ? 'font-bold' : '' }} text-black hover:font-bold">Transaksi</a>

                    {{-- User menu di mobile --}}
                    <hr class="border-gray-300">
                    <a href="{{ url('/user/change-password') }}" class="text-sm hover:font-bold">Ganti Password</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:font-bold">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>





    {{-- konten utama --}}
    <main class="flex justify-center min-h-screen bg-white">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- footer --}}
    <footer class="bg-white py-4 text-center text-sm text-gray-500 shadow-inner">
        &copy; {{ date('Y') }} Copyright by Ansthelabel.
    </footer>

    @stack('scripts')
</body>

</html>
