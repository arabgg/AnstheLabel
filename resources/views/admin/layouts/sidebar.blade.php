<div x-data="{ sidebarOpen: true }" :class="sidebarOpen ? 'w-64' : 'w-16'"
    class="hidden md:flex flex-col bg-[#FBE9EB] shadow-md min-h-screen transition-all duration-300">

    {{-- Logo --}}
    <div class="p-4 flex items-center justify-center">
        <a href="/">
            <img src="{{ asset('storage/images/ansthelabel.png') }}" class="h-10" alt="AnstheLabel Logo">
        </a>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 space-y-2 p-4">
        <a href="/dashboard"
            class="block px-3 py-2 rounded hover:bg-pink-100 {{ request()->is('dashboard*') ? 'font-bold' : '' }}">Dashboard</a>
        <a href="/produk"
            class="block px-3 py-2 rounded hover:bg-pink-100 {{ request()->is('produk*') ? 'font-bold' : '' }}">Produk</a>
        <a href="/kategori"
            class="block px-3 py-2 rounded hover:bg-pink-100 {{ request()->is('kategori*') ? 'font-bold' : '' }}">Kategori</a>
        <a href="/ukuran"
            class="block px-3 py-2 rounded hover:bg-pink-100 {{ request()->is('ukuran*') ? 'font-bold' : '' }}">Ukuran</a>
        <a href="/warna"
            class="block px-3 py-2 rounded hover:bg-pink-100 {{ request()->is('warna*') ? 'font-bold' : '' }}">Warna</a>
        <a href="/metode_pembayaran"
            class="block px-3 py-2 rounded hover:bg-pink-100 {{ request()->is('metode_pembayaran*') ? 'font-bold' : '' }}">Metode
            Pembayaran</a>
        <a href="/transaksi"
            class="block px-3 py-2 rounded hover:bg-pink-100 {{ request()->is('transaksi*') ? 'font-bold' : '' }}">Transaksi</a>
    </nav>

    {{-- User Menu --}}
    <div class="p-4 border-t">
        <a href="{{ url('/user/change-password') }}" class="block text-sm hover:font-bold">Ganti Password</a>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="text-sm text-red-600 hover:font-bold">Logout</button>
        </form>
    </div>
</div>
