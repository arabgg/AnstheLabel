<footer class="footer-section">
  <nav class="footer-section-menu">
    <a href="{{ route('home') }}" class="nav-item {{ request()->is('home') ? 'active' : '' }}">Home</a>
    <a href="{{ route('collection') }}" class="nav-item {{ request()->is('collection') ? 'active' : '' }}">Collection</a>
    <a href="{{ route('about') }}" class="nav-item {{ request()->is('about') ? 'active' : '' }}">About Us</a>
    <a href="#">Contact Us</a>
    <a href="#">Follow Us</a>
  </nav>

  <div class="footer-section-social">
    <a href="#"><i class="fab fa-instagram"></i></a>
    <a href="#"><i class="fab fa-tiktok"></i></a>
    <a href="#"><i class="fab fa-whatsapp"></i></a>
    <a href="#"><i class="fab fa-facebook"></i></a>
  </div>

  <p class="footer-section-text">© Copyright by Ansthelabel</p>
</footer>
