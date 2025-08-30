<div x-data="{ sidebarOpen: true }" class="flex">
    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'w-64' : 'w-16'"
         class="bg-[#560024] text-white h-screen transition-all duration-300 flex flex-col">

        <!-- Toggle Button -->
        <button @click="sidebarOpen = !sidebarOpen"
                class="p-3 focus:outline-none hover:bg-[#6e0030] transition">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Sidebar Content -->
        <nav class="flex-1 mt-4 space-y-2">
            <a href="{{ url('/dashboard') }}" 
               class="flex items-center px-4 py-2 hover:bg-[#6e0030] rounded-md transition">
                <i class="fa fa-home mr-2"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
            </a>
            <a href="{{ url('/produk') }}" 
               class="flex items-center px-4 py-2 hover:bg-[#6e0030] rounded-md transition">
                <i class="fa fa-box mr-2"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Produk</span>
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
</div>