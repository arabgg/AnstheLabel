<!-- Top Bar -->
<div class="top-bar">
    <div class="container top-bar-content">
        <div class="top-left">
            <div class="language-switcher {{ app()->getLocale() === 'id' ? 'id-active' : 'en-active' }}">
                <a href="{{ route('change.language', ['locale' => 'en']) }}">EN</a> /
                <a class="language-id" href="{{ route('change.language', ['locale' => 'id']) }}">ID</a>
            </div>
        </div>

        <div class="top-center">
            {{ $desc->deskripsi }}
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
        <button class="menu-toggle" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <div class="logo">
            <a href="{{ route('home') }}" class="produk-card-link">
                <img src="{{ route('storage', ['folder' => 'page', 'filename' => 'ansthelabel.png']) }}"
                    alt="Ansthelabel Logo">
            </a>
        </div>

        <nav class="nav-links">
            <a href="{{ route('home') }}" class="nav-item {{ request()->is('home') ? 'active' : '' }}">{{ __('messages.page.home') }}</a>
            <a href="{{ route('collection') }}" class="nav-item {{ request()->is('collection') ? 'active' : '' }}">{{ __('messages.page.collection') }}</a>
            <a href="{{ route('invoice') }}" class="nav-item {{ request()->is('invoice') ? 'active' : '' }}">{{ __('messages.page.transaction') }}</a>
            <a href="{{ route('about') }}" class="nav-item {{ request()->is('about') ? 'active' : '' }}">{{ __('messages.page.about') }}</a>
        </nav>
        
        <div class="nav-icons">
            <a href="{{ route('cart.index') }}" class="nav-icon relative">
                <i class="fas fa-cart-shopping"></i>
                @if ($cartCount > 0)
                    <span class="cart-badge">{{ $cartCount }}</span>
                @endif
            </a>
        </div>
    </div>
</header>
