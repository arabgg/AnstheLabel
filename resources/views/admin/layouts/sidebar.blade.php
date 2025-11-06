<div class="flex flex-col fixed top-0 left-0 h-screen w-60 bg-gray-100 text-gray-600">
    {{-- Logo --}}
    <div class="p-4 flex items-center justify-center">
        <a href="/admin">
            <img src="{{ route('storage', ['folder' => 'page', 'filename' => 'ansthelabel.png']) }}" class="h-10"
                alt="Logo">
        </a>
    </div>

    {{-- Pencarian Menu --}}
    <div class="px-3 mb-2">
        <input type="text" id="menu-search" placeholder="Cari menu..."
            class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-red-400">
    </div>

    {{-- Menu Scrollable --}}
    <nav id="sidebar-menu" class="flex-1 p-2 overflow-y-auto space-y-6">

        {{-- Section: Main Menu --}}
        <div class="flex flex-col space-y-1">
            <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-500 px-3 mt-2">Menu Utama</h2>
            <hr class="border-t border-gray-300 mx-3">

            {{-- Dashboard hanya untuk super_admin --}}
            @if (Auth::user()->role === 'super_admin')
                <a href="/admin"
                    class="menu-item flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-red-100 transition text-sm
                    {{ request()->is('admin*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                    <i class="fa-solid fa-home w-5 text-center min-w-[20px]"></i>
                    <span class="flex-1 leading-none">Dashboard</span>
                </a>
            @endif

            <a href="/banner"
                class="menu-item flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-red-100 transition text-sm
                {{ request()->is('banner*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                <i class="fa-solid fa-image w-5 text-center min-w-[20px]"></i>
                <span class="flex-1 leading-none">Banner</span>
            </a>
            <a href="/faq"
                class="menu-item flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-red-100 transition text-sm
                {{ request()->is('faq*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                <i class="fa-solid fa-question-circle w-5 text-center min-w-[20px]"></i>
                <span class="flex-1 leading-none">FAQ</span>
            </a>
        </div>

        {{-- Section: Kelola Produk --}}
        <div class="flex flex-col space-y-1">
            <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-500 px-3 mt-2">Menu Produk</h2>
            <hr class="border-t border-gray-300 mx-3">
            @foreach ([['url' => '/produk', 'icon' => 'fa-boxes-stacked', 'label' => 'Produk'],
                ['url' => '/stok', 'icon' => 'fa-boxes-stacked', 'label' => 'Stok Produk'], 
                ['url' => '/kategori', 'icon' => 'fa-tag', 'label' => 'Kategori'], 
                ['url' => '/bahan', 'icon' => 'fa-leaf', 'label' => 'Bahan'], 
                ['url' => '/ukuran', 'icon' => 'fa-ruler', 'label' => 'Ukuran'], 
                ['url' => '/warna', 'icon' => 'fa-paintbrush', 'label' => 'Warna']] as $item)
                <a href="{{ $item['url'] }}"
                    class="menu-item flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-red-100 transition text-sm
                    {{ request()->is(ltrim($item['url'], '/') . '*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                    <i class="fa-solid {{ $item['icon'] }} w-5 text-center min-w-[20px]"></i>
                    <span class="flex-1 leading-none">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Section: Transaksi --}}
        <div class="flex flex-col space-y-1">
            <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-500 px-3 mt-2">Menu Transaksi</h2>
            <hr class="border-t border-gray-300 mx-3">
            <a href="/voucher"
                class="menu-item flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-red-100 transition text-sm
                {{ request()->is('voucher*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                <i class="fa-solid fa-gift w-5 text-center min-w-[20px]"></i>
                <span class="flex-1 leading-none">Voucher</span>
            </a>
            <a href="/ekspedisi"
                class="menu-item flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-red-100 transition text-sm
                {{ request()->is('ekspedisi*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                <i class="fa-solid fa-truck w-5 text-center min-w-[20px]"></i>
                <span class="flex-1 leading-none">Ekspedisi</span>
            </a>
            
            {{-- Daftar Pesanan hanya untuk super_admin --}}
            @if (Auth::user()->role === 'super_admin')
                <a href="/metode_pembayaran"
                    class="menu-item flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-red-100 transition text-sm
                {{ request()->is('metode_pembayaran*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                    <i class="fa-solid fa-credit-card w-5 text-center min-w-[20px]"></i>
                    <span class="flex-1 leading-none">Metode Pembayaran</span>
                </a>
                <a href="/pesanan"
                    class="menu-item flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-red-100 transition text-sm
                    {{ request()->is('pesanan*') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
                    <i class="fa-solid fa-receipt w-5 text-center min-w-[20px]"></i>
                    <span class="flex-1 leading-none">Daftar Pesanan</span>
                </a>
            @endif
        </div>
    </nav>

    {{-- Menu Admin (Selalu di bawah) --}}
    <div class="p-2 border-t border-gray-300">
        <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-500 px-3 mb-2">Menu Admin</h2>
        <a href="{{ route('auth.change-password.form') }}"
            class="menu-item flex items-center gap-1 px-3 py-1 rounded-lg hover:bg-red-100 transition text-xs
            {{ request()->routeIs('auth.change-password.form') ? 'font-bold text-black bg-red-200 border-l-4 border-[#560024]' : '' }}">
            <i class="fa-solid fa-key w-5 text-center min-w-[20px]"></i>
            <span class="flex-1 leading-none">Ganti Kata Sandi</span>
        </a>

        <a href="#" id="logout-button"
            class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-red-100 transition text-xs text-red-600">
            <i class="fa-solid fa-sign-out-alt w-5 text-center min-w-[20px]"></i>
            <span class="flex-1 leading-none">Keluar</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">@csrf</form>
    </div>

    {{-- Script: Logout & Pencarian Menu --}}
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

        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("menu-search");
            const sections = document.querySelectorAll("#sidebar-menu .flex.flex-col.space-y-1");

            function filterMenu() {
                const filter = searchInput.value.toLowerCase();

                sections.forEach(section => {
                    const items = section.querySelectorAll(".menu-item");
                    let visibleCount = 0;

                    items.forEach(item => {
                        const text = item.innerText.toLowerCase();
                        if (text.includes(filter)) {
                            item.style.display = "flex";
                            visibleCount++;
                        } else {
                            item.style.display = "none";
                        }
                    });

                    const header = section.querySelector("h2");
                    const hr = section.querySelector("hr");

                    if (visibleCount === 0) {
                        section.style.display = "none";
                    } else {
                        section.style.display = "flex";
                        section.style.flexDirection = "column";
                        if (header) header.style.display = "block";
                        if (hr) hr.style.display = "block";
                    }
                });
            }

            searchInput.addEventListener("keyup", filterMenu);

            searchInput.addEventListener("keydown", function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    const firstVisible = document.querySelector(
                        "#sidebar-menu .menu-item[style*='display: flex']");
                    if (firstVisible) {
                        window.location.href = firstVisible.getAttribute("href");
                    }
                }
            });
        });
    </script>
</div>
