<!-- Top Bar -->
<div class="top-bar">
    <div class="container top-bar-content">
        <div class="top-center">
            Enjoy Free Shipping On All Orders
        </div>
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
    </div>
</header>
