<div class="flex flex-col fixed top-0 left-0 h-screen w-60 bg-gray-100 text-gray-600">
    {{-- Logo --}}
    <div class="p-4 flex items-center justify-center">
        <a href="/admin">
            <img src="{{ route('storage', ['folder' => 'page', 'filename' => 'ansthelabel.png']) }}" class="h-10"
                alt="Logo">
        </a>
    </div>

    {{-- Menu Scrollable --}}
    <nav class="flex-1 p-2 overflow-y-auto space-y-6">

        {{-- Section: Main Menu --}}
        <div class="flex flex-col space-y-1">
            <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-500 px-3 mt-2">Menu Utama</h2>
            <hr class="border-t border-gray-300 mx-3">
            <a href="/admin"
                class="flex items-center gap-2 px-3 py-2 rounded hover:bg-red-100 transition text-sm
                {{ request()->is('admin*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                <i class="fa-solid fa-home w-5 text-center min-w-[20px]"></i>
                <span class="flex-1 leading-none">Dasbor</span>
            </a>
            <a href="/banner"
                class="flex items-center gap-2 px-3 py-2 rounded hover:bg-red-100 transition text-sm
                {{ request()->is('banner*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                <i class="fa-solid fa-image w-5 text-center min-w-[20px]"></i>
                <span class="flex-1 leading-none">Kelola Spanduk</span>
            </a>
        </div>

        {{-- Section: Kelola Produk --}}
        <div class="flex flex-col space-y-1">
            <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-500 px-3 mt-2">Kelola Produk</h2>
            <hr class="border-t border-gray-300 mx-3">
            @foreach ([['url' => '/produk', 'icon' => 'fa-boxes-stacked', 'label' => 'Produk'], ['url' => '/kategori', 'icon' => 'fa-tag', 'label' => 'Kategori'], ['url' => '/bahan', 'icon' => 'fa-leaf', 'label' => 'Bahan'], ['url' => '/ukuran', 'icon' => 'fa-ruler', 'label' => 'Ukuran'], ['url' => '/warna', 'icon' => 'fa-paintbrush', 'label' => 'Warna']] as $item)
                <a href="{{ $item['url'] }}"
                    class="flex items-center gap-2 px-3 py-2 rounded hover:bg-red-100 transition text-sm
                    {{ request()->is(ltrim($item['url'], '/') . '*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                    <i class="fa-solid {{ $item['icon'] }} w-5 text-center min-w-[20px]"></i>
                    <span class="flex-1 leading-none">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Section: Transaksi --}}
        <div class="flex flex-col space-y-1">
            <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-500 px-3 mt-2">Transaksi</h2>
            <hr class="border-t border-gray-300 mx-3">
            <a href="/metode_pembayaran"
                class="flex items-center gap-2 px-3 py-2 rounded hover:bg-red-100 transition text-sm
                {{ request()->is('metode_pembayaran*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                <i class="fa-solid fa-credit-card w-5 text-center min-w-[20px]"></i>
                <span class="flex-1 leading-none">Metode Pembayaran</span>
            </a>
            <a href="/pesanan"
                class="flex items-center gap-2 px-3 py-2 rounded hover:bg-red-100 transition text-sm
                {{ request()->is('pesanan*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                <i class="fa-solid fa-receipt w-5 text-center min-w-[20px]"></i>
                <span class="flex-1 leading-none">Daftar Pesanan</span>
            </a>
        </div>
    </nav>

    {{-- Menu Admin (Selalu di bawah) --}}
    <div class="p-2 border-t border-gray-300">
        <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-500 px-3 mb-2">Menu Admin</h2>
        <a href="{{ route('auth.change-password.form') }}"
            class="flex items-center gap-1 px-3 py-1 rounded hover:bg-red-100 transition text-xs
            {{ request()->routeIs('auth.change-password.form') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
            <i class="fa-solid fa-key w-5 text-center min-w-[20px]"></i>
            <span class="flex-1 leading-none">Ganti Kata Sandi</span>
        </a>

        <a href="#" id="logout-button"
            class="flex items-center gap-2 px-3 py-2 rounded hover:bg-red-100 transition text-xs text-red-600">
            <i class="fa-solid fa-sign-out-alt w-5 text-center min-w-[20px]"></i>
            <span class="flex-1 leading-none">Keluar</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">@csrf</form>
    </div>

    {{-- SweetAlert: Logout --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutButton = document.getElementById('logout-button');
            const logoutForm = document.getElementById('logout-form');

            logoutButton.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Anda akan keluar dari sesi!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#560024',
                    confirmButtonText: 'Ya, Logout!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        logoutForm.submit();
                    }
                });
            });
        });
    </script>
</div>
