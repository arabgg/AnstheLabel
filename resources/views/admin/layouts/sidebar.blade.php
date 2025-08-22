<div x-data="{ sidebarOpen: true }" class="flex">
    {{-- Sidebar --}}
    <div :class="sidebarOpen ? 'w-64' : 'w-16'"
        class="hidden md:flex flex-col fixed top-0 left-0 h-screen bg-gray-100 shadow-md transition-all duration-300">

        {{-- Logo + Tombol Toggle --}}
        <div class="p-4 flex items-center justify-center relative">
            <a href="/" x-show="sidebarOpen" x-transition>
                <img src="{{ asset('storage/images/ansthelabel.png') }}" class="h-10" alt="AnstheLabel Logo">
            </a>
            <a href="/" x-show="!sidebarOpen" x-transition>
                <img src="{{ asset('storage/images/ansthelabel-icon.png') }}" class="h-8" alt="Icon">
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
        <nav class="flex-1 space-y-2 p-2 overflow-y-auto">
            {{-- Dashboard --}}
            <a href="/dashboard" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-300 transition"
                :class="{{ request()->is('dashboard*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                <i class="fa-solid fa-chart-line h-5 w-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>Dashboard</span>
            </a>

            {{-- Produk --}}
            <a href="/produk" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-300 transition"
                :class="{{ request()->is('produk*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span x-show="sidebarOpen" x-transition>Produk</span>
            </a>

            {{-- Kategori --}}
            <a href="/kategori" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-300 transition"
                :class="{{ request()->is('kategori*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                <i class="fa-solid fa-tag"></i>
                <span x-show="sidebarOpen" x-transition>Kategori</span>
            </a>

            {{-- Bahan --}}
            <a href="/bahan" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-300 transition"
                :class="{{ request()->is('bahan*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                <i class="fa-solid fa-leaf"></i>
                <span x-show="sidebarOpen" x-transition>Bahan</span>
            </a>

            {{-- Ukuran --}}
            <a href="/ukuran" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-300 transition"
                :class="{{ request()->is('ukuran*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                <i class="fa-solid fa-ruler"></i>
                <span x-show="sidebarOpen" x-transition>Ukuran</span>
            </a>

            {{-- Warna --}}
            <a href="/warna" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-300 transition"
                :class="{{ request()->is('warna*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                <i class="fa-solid fa-paintbrush"></i>
                <span x-show="sidebarOpen" x-transition>Warna</span>
            </a>

            {{-- Metode Pembayaran --}}
            <a href="/metode_pembayaran" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-300 transition"
                :class="{{ request()->is('metode_pembayaran*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                <i class="fa-solid fa-credit-card"></i>
                <span x-show="sidebarOpen" x-transition>Metode Pembayaran</span>
            </a>

            {{-- Transaksi --}}
            <a href="/transaksi" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-300 transition"
                :class="{{ request()->is('transaksi*') ? "'font-bold text-white bg-[#560024]'" : "''" }}">
                <i class="fa-solid fa-receipt"></i>
                <span x-show="sidebarOpen" x-transition>Transaksi</span>
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

    {{-- Main Section --}}
    <div :class="sidebarOpen ? 'ml-60' : 'ml-11'" class="flex-1 transition-all duration-300 p-4">
    </div>
</div>
