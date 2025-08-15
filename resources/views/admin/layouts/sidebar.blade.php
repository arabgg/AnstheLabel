<div x-data="{ sidebarOpen: true }" class="flex">
    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'w-64' : 'w-16'"
        class="hidden md:flex flex-col fixed top-0 left-0 h-screen bg-[#FBE9EB] shadow-md transition-all duration-300">

        {{-- Logo + Tombol Toggle --}}
        <div class="p-4 flex items-center justify-center relative">
            <a href="/" x-show="sidebarOpen" x-transition>
                <img src="{{ asset('storage/images/ansthelabel.png') }}" class="h-10" alt="AnstheLabel Logo">
            </a>
            <a href="/" x-show="!sidebarOpen" x-transition>
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
        <nav class="flex-1 space-y-2 p-2 overflow-y-auto">
            <!-- Dashboard -->
            <a href="/dashboard"
                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-pink-400 transition"
                :class="{{ request()->is('dashboard*') ? "'font-bold bg-pink-400'" : "''" }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7m-9 2v6m0 0h4m-4 0H7" />
                </svg>
                <span x-show="sidebarOpen" x-transition>Dashboard</span>
            </a>

            <!-- Produk -->
            <a href="/produk"
                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-pink-400 transition"
                :class="{{ request()->is('produk*') ? "'font-bold bg-pink-400'" : "''" }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7M5 18h14a2 2 0 002-2v-5H3v5a2 2 0 002 2z" />
                </svg>
                <span x-show="sidebarOpen" x-transition>Produk</span>
            </a>

            <!-- Kategori -->
            <a href="/kategori"
                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-pink-400 transition"
                :class="{{ request()->is('kategori*') ? "'font-bold bg-pink-400'" : "''" }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                <span x-show="sidebarOpen" x-transition>Kategori</span>
            </a>

            <!-- Ukuran -->
            <a href="/ukuran"
                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-pink-400 transition"
                :class="{{ request()->is('ukuran*') ? "'font-bold bg-pink-400'" : "''" }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h4a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm10 0a1 1 0 011-1h4a1 1 0 011 1v16a1 1 0 01-1 1h-4a1 1 0 01-1-1V4z" />
                </svg>
                <span x-show="sidebarOpen" x-transition>Ukuran</span>
            </a>

            <!-- Warna -->
            <a href="/warna"
                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-pink-400 transition"
                :class="{{ request()->is('warna*') ? "'font-bold bg-pink-400'" : "''" }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                <span x-show="sidebarOpen" x-transition>Warna</span>
            </a>

            <!-- Metode Pembayaran -->
            <a href="/metode_pembayaran"
                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-pink-400 transition"
                :class="{{ request()->is('metode_pembayaran*') ? "'font-bold bg-pink-400'" : "''" }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 7h16M4 11h16M4 15h16M4 19h16" />
                </svg>
                <span x-show="sidebarOpen" x-transition>Metode Pembayaran</span>
            </a>

            <!-- Transaksi -->
            <a href="/transaksi"
                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-pink-400 transition"
                :class="{{ request()->is('transaksi*') ? "'font-bold bg-pink-400'" : "''" }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>
                <span x-show="sidebarOpen" x-transition>Transaksi</span>
            </a>

            {{-- User Menu --}}
            <div class="p-4 border-t">
                <a href="{{ url('/user/change-password') }}" class="flex items-center gap-3 text-sm hover:font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c0-1.657-1.343-3-3-3S6 9.343 6 11v2c0 1.657 1.343 3 3 3s3-1.343 3-3v-2z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition>Ganti Password</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2 flex items-center gap-3">
                    @csrf
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0 text-red-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7" />
                    </svg>
                    <button type="submit" x-show="sidebarOpen" x-transition
                        class="text-sm text-red-600 hover:font-bold">Logout</button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Main Section -->
    <div :class="sidebarOpen ? 'ml-60' : 'ml-11'" class="flex-1 transition-all duration-300 p-4">
    </div>
</div>
