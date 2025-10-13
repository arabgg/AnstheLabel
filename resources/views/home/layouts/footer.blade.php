<footer class="footer-section">
  <nav class="footer-section-menu">
    <a href="{{ route('home') }}" class="footer-item {{ request()->is('home') ? 'active' : '' }}">Home</a>
    <a href="{{ route('collection') }}" class="footer-item {{ request()->is('collection') ? 'active' : '' }}">Collection</a>
    <a href="{{ route('about') }}" class="footer-item {{ request()->is('about') ? 'active' : '' }}">About Us</a>
    <a href="#">Contact Us</a>
    <a href="{{ route('homefaq') }}" class="footer-item {{ request()->is('faq') ? 'active' : '' }}">FAQ</a>
  </nav>

  <div class="footer-section-social">
    <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
    <a href="https://www.tiktok.com/"><i class="fab fa-tiktok"></i></a>
    <a href="https://www.whatsapp.com/"><i class="fab fa-whatsapp"></i></a>
    <a href="https://web.facebook.com/"><i class="fab fa-facebook"></i></a>
  </div>

  <p class="footer-section-text">&copy; {{ date('Y') . ' ' . __('messages.page.copy') }}</p>
</footer>
