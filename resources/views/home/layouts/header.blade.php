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
                <img src="{{ route('storage', ['folder' => 'page', 'filename' => 'ansthelabel.png']) }}" alt="Ansthelabel Logo">
            </a>
        </div>
        <nav class="nav-links">
            <a href="{{ route('home') }}" class="nav-item {{ request()->is('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('collection') }}" class="nav-item {{ request()->is('collection') ? 'active' : '' }}">Collection</a>
            <a href="{{ route('invoice') }}" class="nav-item {{ request()->is('invoice') ? 'active' : '' }}">Transaction</a>
            <a href="{{ route('about') }}" class="nav-item {{ request()->is('about') ? 'active' : '' }}">About Us</a>
        </nav>
        <div class="nav-icons">
            <a href="{{ route('cart.index') }}" class="nav-icon relative">
                <i class="fas fa-cart-shopping"></i>
                @if($cartCount > 0)
                    <span class="cart-badge">{{ $cartCount }}</span>
                @endif
            </a>
        </div>
    </div>
</header>
