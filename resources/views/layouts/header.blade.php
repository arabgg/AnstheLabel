<!-- Top Bar -->
<div class="top-bar">
    <div class="container top-bar-content">
        {{-- <div class="top-left">
            <span><i class="fa fa-phone"></i> +62XXXXXX</span>
            <span><i class="fa fa-envelope"></i> ansthelabel@xxxxx.com</span>
        </div> --}}
        <div class="top-center">
            Enjoy Free Shipping On All Orders
        </div>
        {{-- <div class="top-right">
            <span>Follow Us :</span>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-tiktok"></i></a>
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
        </div> --}}
    </div>
</div>

<!-- Navigation Bar -->
<header class="main-header">
    <div class="container nav-content">
        <div class="logo">
            <img src="{{ asset('storage/images/ansthelabel.png') }}" alt="Ansthelabel Logo">
        </div>
        <nav class="nav-links">
            <a href="{{ route('home') }}" class="nav-item {{ request()->is('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('collection') }}" class="nav-item {{ request()->is('collection') ? 'active' : '' }}">Collection</a>
            <a href="{{ route('about') }}" class="nav-item {{ request()->is('about') ? 'active' : '' }}">About Us</a>
        </nav>
        {{-- <div class="nav-icons">
            <a href="#"><i class="far fa-user"></i></a>
            <a href="#"><i class="far fa-heart"></i></a>
            <a href="#"><i class="fas fa-shopping-cart"></i></a>
        </div> --}}
    </div>
</header>
