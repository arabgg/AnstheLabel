<div x-data="{ sidebarOpen: true }" class="flex">
    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'w-64' : 'w-16'"
        class="hidden md:flex flex-col fixed top-0 left-0 h-screen bg-[#FBE9EB] shadow-md transition-all duration-300">

        {{-- Logo + Tombol Toggle --}}
        <div class="p-4 flex items-center justify-center relative">
            <a href="/" x-show="sidebarOpen">
                <img src="{{ asset('storage/images/ansthelabel.png') }}" class="h-10" alt="AnstheLabel Logo">
            </a>
            <a href="/" x-show="!sidebarOpen">
                <img src="{{ asset('storage/images/ansthelabel.png') }}" class="h-8" alt="Logo">
            </a>
            <button @click="sidebarOpen = !sidebarOpen"
                class="absolute top-4 right-[-12px] bg-white rounded-full shadow p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        :d="sidebarOpen ? 'M15 19l-7-7 7-7' : 'M9 5l7 7-7 7'" />
                </svg>
            </button>
        </div>

        {{-- Menu --}}
        <nav class="flex-1 space-y-2 p-4 overflow-y-auto" x-show="sidebarOpen" x-transition>
            <a href="/dashboard"
                class="block px-3 py-2 rounded hover:bg-pink-100 {{ request()->is('dashboard*') ? 'font-bold' : '' }}">
                Dashboard
            </a>
            <a href="/produk"
                class="block px-3 py-2 rounded hover:bg-pink-100 {{ request()->is('produk*') ? 'font-bold' : '' }}">
                Produk
            </a>
            <a href="/kategori"
                class="block px-3 py-2 rounded hover:bg-pink-100 {{ request()->is('kategori*') ? 'font-bold' : '' }}">
                Kategori
            </a>
            <a href="/ukuran"
                class="block px-3 py-2 rounded hover:bg-pink-100 {{ request()->is('ukuran*') ? 'font-bold' : '' }}">
                Ukuran
            </a>
            <a href="/warna"
                class="block px-3 py-2 rounded hover:bg-pink-100 {{ request()->is('warna*') ? 'font-bold' : '' }}">
                Warna
            </a>
            <a href="/metode_pembayaran"
                class="block px-3 py-2 rounded hover:bg-pink-100 {{ request()->is('metode_pembayaran*') ? 'font-bold' : '' }}">
                Metode Pembayaran
            </a>
            <a href="/transaksi"
                class="block px-3 py-2 rounded hover:bg-pink-100 {{ request()->is('transaksi*') ? 'font-bold' : '' }}">
                Transaksi
            </a>

            {{-- User Menu --}}
            <div class="p-4 border-t">
                <a href="{{ url('/user/change-password') }}" class="block text-sm hover:font-bold">Ganti Password</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:font-bold">Logout</button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Main Section -->
    <div :class="sidebarOpen ? 'ml-64' : 'ml-16'" class="flex-1 transition-all duration-300 p-4">
    </div>
</div>
