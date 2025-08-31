<div class="block md:hidden" x-data="{ open: false }">
    <nav class="bg-[#FBE9EB] px-6 py-4 shadow font-montserrat flex justify-between items-center">
        {{-- Logo --}}
        <a href="/">
            <img src="{{ asset('storage/images/ansthelabel.png') }}" class="h-10" alt="AnstheLabel Logo">
        </a>
        {{-- Hamburger --}}
        <button @click="open = !open">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </nav>

    {{-- Menu Mobile --}}
    <div x-show="open" class="absolute top-16 left-0 w-full bg-pink-200 shadow-md flex flex-col space-y-4 p-4">
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
        <hr class="border-gray-300">
        <a href="{{ url('/user/change-password') }}" class="text-sm hover:font-bold">Ganti Password</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-red-600 hover:font-bold">Logout</button>
        </form>
    </div>
</div>
