<!-- Top Bar -->
<div class="top-bar">
    <div class="container top-bar-content">
        <div class="top-center">
            Enjoy Free Shipping On All Orders
        </div>

        <div class="top-right">
            <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
            <a href="https://www.tiktok.com/"><i class="fab fa-tiktok"></i></a> 
            <a href="https://web.facebook.com/"><i class="fab fa-facebook"></i></a>
        </div>
    </div>
</div>

<!-- Navigation Bar -->
<header class="main-header" id="mainHeader">
    <div class="container nav-content">
        <div class="logo">
            <a href="{{ route('home') }}" class="produk-card-link">
                <img src="{{ asset('storage/images/ansthelabel.png') }}" alt="Ansthelabel Logo">
            </a>
        </div>
        <nav class="nav-links">
            <a href="{{ route('home') }}" class="nav-item {{ request()->is('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('collection') }}" class="nav-item {{ request()->is('collection') ? 'active' : '' }}">Collection</a>
            <a href="{{ route('collection') }}" class="nav-item {{ request()->is('collection') ? 'active' : '' }}">Cek Transaksi</a>
            <a href="{{ route('about') }}" class="nav-item {{ request()->is('about') ? 'active' : '' }}">About Us</a>
        </nav>
        <div class="nav-icons">
            <a href="#">
                <i class="fas fa-cart-shopping"></i>
            </a>
        </div>
    </div>
</header>
