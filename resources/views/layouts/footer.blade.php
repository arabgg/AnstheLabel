<footer class="footer-section">
  <nav class="footer-section-menu">
    <a href="{{ route('page') }}" class="nav-item {{ request()->is('page') ? 'active' : '' }}">Home</a>
    <a href="{{ route('collection') }}" class="nav-item {{ request()->is('collection') ? 'active' : '' }}">Collection</a>
    <a href="{{ route('about') }}" class="nav-item {{ request()->is('about') ? 'active' : '' }}">About Us</a>
    <a href="#">Contact Us</a>
    <a href="#">Follow Us</a>
  </nav>

  <div class="footer-section-social">
    <a href="#" class="footer-section-shopee-icon">
      <img src="{{ asset('storage/icon/shopee.png') }}" alt="Shopee" />
    </a>
    <a href="#"><i class="fab fa-instagram"></i></a>
    <a href="#"><i class="fab fa-facebook"></i></a>
    <a href="#"><i class="fab fa-pinterest"></i></a>
    <a href="#"><i class="fab fa-tiktok"></i></a>
  </div>

  <p class="footer-section-text">© Lorem Ipsum dolor sit amet 2023.</p>
</footer>
