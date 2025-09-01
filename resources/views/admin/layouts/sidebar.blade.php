<div
    class="hidden md:flex flex-col fixed top-0 left-0 h-screen bg-gray-100 shadow-md transition-all duration-300"
    :class="sidebarOpen ? 'w-64' : 'w-16'"
>
    {{-- Logo + Toggle --}}
    <div class="p-4 flex items-center justify-center relative">
        <a href="/" x-show="sidebarOpen" x-transition>
            <img src="{{ route('storage', ['folder' => 'page', 'filename' => 'ansthelabel.png']) }}"
                 class="h-10" alt="AnstheLabel Logo">
        </a>
        <a href="/" x-show="!sidebarOpen" x-transition>
            <img src="{{ route('storage', ['folder' => 'page', 'filename' => 'ansthelabel-icon.png']) }}"
                 class="h-8" alt="Icon">
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
    <nav class="flex-1 p-2 overflow-y-auto space-y-2" x-data="{ 
        collectionOpen: {{ request()->is('produk*') || request()->is('kategori*') || request()->is('bahan*') || request()->is('ukuran*') || request()->is('warna*') ? 'true' : 'false' }}, 
        transaksiOpen: {{ request()->is('transaksi*') || request()->is('metode-pembayaran*') ? 'true' : 'false' }} 
    }">
        
        {{-- Dashboard --}}
        <a href="/admin"
           class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-300 transition"
           :class="{{ request()->is('admin*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
            <i class="fa-solid fa-chart-line h-5 w-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" x-transition>Dashboard</span>
        </a>

        {{-- Collection --}}
        <div>
            <button @click="collectionOpen = !collectionOpen"
                    class="flex items-center gap-3 px-3 py-2 w-full rounded hover:bg-red-300 transition focus:outline-none"
                    :class="collectionOpen ? 'font-bold text-white bg-[#560024]' : ''">
                <i class="fa-solid fa-layer-group"></i>
                <span x-show="sidebarOpen" x-transition>Collection</span>
                <svg x-show="sidebarOpen" :class="collectionOpen ? 'rotate-90' : ''"
                     class="ml-auto h-4 w-4 transition-transform" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            {{-- Sub-menu --}}
            <div x-show="collectionOpen" x-collapse class="flex flex-col pl-8 space-y-1">
                <a href="/produk"
                   class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-200 transition"
                   :class="{{ request()->is('produk*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span x-show="sidebarOpen" x-transition>Produk</span>
                </a>
                <a href="/kategori"
                   class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-200 transition"
                   :class="{{ request()->is('kategori*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                    <i class="fa-solid fa-tag"></i>
                    <span x-show="sidebarOpen" x-transition>Kategori</span>
                </a>
                <a href="/bahan"
                   class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-200 transition"
                   :class="{{ request()->is('bahan*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                    <i class="fa-solid fa-leaf"></i>
                    <span x-show="sidebarOpen" x-transition>Bahan</span>
                </a>
                <a href="/ukuran"
                   class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-200 transition"
                   :class="{{ request()->is('ukuran*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                    <i class="fa-solid fa-ruler"></i>
                    <span x-show="sidebarOpen" x-transition>Ukuran</span>
                </a>
                <a href="/warna"
                   class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-200 transition"
                   :class="{{ request()->is('warna*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                    <i class="fa-solid fa-paintbrush"></i>
                    <span x-show="sidebarOpen" x-transition>Warna</span>
                </a>
            </div>
        </div>

        {{-- Transaksi --}}
        <div>
            <button @click="transaksiOpen = !transaksiOpen"
                    class="flex items-center gap-3 px-3 py-2 w-full rounded hover:bg-red-300 transition focus:outline-none"
                    :class="transaksiOpen ? 'font-bold text-white bg-[#560024]' : ''">
                <i class="fa-solid fa-receipt"></i>
                <span x-show="sidebarOpen" x-transition>Transaksi</span>
                <svg x-show="sidebarOpen" :class="transaksiOpen ? 'rotate-90' : ''"
                     class="ml-auto h-4 w-4 transition-transform" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <div x-show="transaksiOpen" x-collapse class="flex flex-col pl-8 space-y-1">
                <a href="/metode_pembayaran"
                   class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-200 transition"
                   :class="{{ request()->is('metode-pembayaran*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                    <i class="fa-solid fa-credit-card"></i>
                    <span x-show="sidebarOpen" x-transition>Metode Pembayaran</span>
                </a>
                <a href="/pesanan"
                   class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-200 transition"
                   :class="{{ request()->is('pesanan*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                    <i class="fa-solid fa-receipt"></i>
                    <span x-show="sidebarOpen" x-transition>List Pesanan</span>
                </a>
            </div>
        </div>

        {{-- Banner Landing Page --}}
        <a href="/banner"
           class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-300 transition"
           :class="{{ request()->is('banner*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
            <i class="fa-solid fa-chart-line h-5 w-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" x-transition>Page Banner</span>
        </a>

        {{-- User Menu --}}
        <div class="p-4 border-t">
            <a href="{{ route('auth.change-password.form') }}"
               class="flex items-center gap-3 text-sm hover:font-bold">
                <i class="fa-solid fa-key"></i>
                <span x-show="sidebarOpen" x-transition>Ganti Password</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2 flex items-center gap-3">
                @csrf
                <i class="fa-solid fa-sign-out-alt"></i>
                <button type="submit" x-show="sidebarOpen" x-transition
                        class="text-sm text-red-600 hover:font-bold">Logout</button>
            </form>
        </div>
    </nav>
</div>
