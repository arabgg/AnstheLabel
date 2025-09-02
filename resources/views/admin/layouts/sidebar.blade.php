<div class="hidden md:flex flex-col fixed top-0 left-0 h-screen w-60 bg-gray-100 text-gray-600">
    {{-- Logo --}}
    <div class="p-4 flex items-center justify-center">
        <a href="/">
            <img src="{{ route('storage', ['folder' => 'page', 'filename' => 'ansthelabel.png']) }}" class="h-10" alt="Logo">
        </a>
    </div>

    {{-- Container menu utama + user menu --}}
    <div class="flex flex-col justify-between h-full">

        {{-- Menu Utama --}}
        <nav class="flex-1 p-2 overflow-y-auto space-y-2">

            {{-- Dashboard --}}
            <a href="/admin" class="flex text-sm items-center gap-3 px-3 py-2 rounded hover:bg-red-100 transition
                {{ request()->is('admin*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                <i class="fa-solid fa-chart-line h-5 w-5 flex-shrink-0 text-center"></i>
                <span>Dashboard</span>
            </a>

            {{-- Collection --}}
            <details 
                {{ request()->is('produk*') || request()->is('kategori*') || request()->is('bahan*') || request()->is('ukuran*') || request()->is('warna*') ? 'open' : '' }}
                class="group"
            >
                <summary class="flex items-center gap-3 px-3 py-2 w-full rounded hover:bg-red-100 cursor-pointer
                    {{ request()->is('produk*') || request()->is('kategori*') || request()->is('bahan*') || request()->is('ukuran*') || request()->is('warna*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                    <i class="fa-solid fa-layer-group h-5 w-5 flex-shrink-0 text-center"></i>
                    <span class="text-sm">Collection</span>
                    <span class="ml-auto">
                        <i class="fa-solid fa-chevron-right h-5 w-5 flex-shrink-0 transition-transform group-open:rotate-90"></i>
                    </span>
                </summary>
                <div class="flex flex-col space-y-1 mt-1">
                    <a href="/produk" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-100 transition
                        {{ request()->is('produk*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                        <i class="fa-solid fa-boxes-stacked h-5 w-5 flex-shrink-0"></i>
                        <span class="text-sm">Produk</span>
                    </a>
                    <a href="/kategori" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-100 transition
                        {{ request()->is('kategori*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                        <i class="fa-solid fa-tag h-5 w-5 flex-shrink-0"></i>
                        <span class="text-sm">Kategori</span>
                    </a>
                    <a href="/bahan" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-100 transition
                        {{ request()->is('bahan*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                        <i class="fa-solid fa-leaf h-5 w-5 flex-shrink-0"></i>
                        <span class="text-sm">Bahan</span>
                    </a>
                    <a href="/ukuran" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-100 transition
                        {{ request()->is('ukuran*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                        <i class="fa-solid fa-ruler h-5 w-5 flex-shrink-0"></i>
                        <span class="text-sm">Ukuran</span>
                    </a>
                    <a href="/warna" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-100 transition
                        {{ request()->is('warna*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                        <i class="fa-solid fa-paintbrush h-5 w-5 flex-shrink-0"></i>
                        <span class="text-sm">Warna</span>
                    </a>
                </div>
            </details>

            {{-- Transaksi --}}
            <details 
                {{ request()->is('transaksi*') || request()->is('metode-pembayaran*') ? 'open' : '' }}
                class="group"
            >
                <summary class="flex items-center gap-3 px-3 py-2 w-full rounded hover:bg-red-100 cursor-pointer
                    {{ request()->is('transaksi*') || request()->is('metode-pembayaran*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                    <i class="fa-solid fa-receipt h-5 w-5 flex-shrink-0 text-center"></i>
                    <span class="text-sm">Transaksi</span>
                    <span class="ml-auto">
                        <i class="fa-solid fa-chevron-right h-5 w-5 flex-shrink-0 transition-transform group-open:rotate-90"></i>
                    </span>
                </summary>
                <div class="flex flex-col space-y-1 mt-1">
                    <a href="/metode-pembayaran" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-100 transition
                        {{ request()->is('metode-pembayaran*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                        <i class="fa-solid fa-credit-card h-5 w-5 flex-shrink-0"></i>
                        <span class="text-sm">Metode Pembayaran</span>
                    </a>
                    <a href="/pesanan" class="flex items-center gap-3 px-4 py-2 rounded hover:bg-red-100 transition
                        {{ request()->is('pesanan*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                        <i class="fa-solid fa-receipt h-5 w-5 flex-shrink-0"></i>
                        <span class="text-sm">List Pesanan</span>
                    </a>
                </div>
            </details>

            {{-- Banner --}}
            <a href="/banner" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-100 transition
               {{ request()->is('banner*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                <i class="fa-solid fa-chart-line h-5 w-5 flex-shrink-0 text-center"></i>
                <span class="text-sm">Page Banner</span>
            </a>

        </nav>

        {{-- User Menu di bawah --}}
        <div class="p-4 border-t">
            <a href="{{ route('auth.change-password.form') }}" class="flex items-center mb-3 gap-3 text-sm hover:font-bold">
                <i class="fa-solid fa-key h-5 w-5 flex-shrink-0"></i>
                <span>Ganti Password</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2 flex items-center gap-3">
                @csrf
                <i class="fa-solid fa-sign-out-alt h-5 w-5 flex-shrink-0"></i>
                <button type="submit" class="text-sm text-red-600 hover:font-bold">Logout</button>
            </form>
        </div>
    </div>
</div>
