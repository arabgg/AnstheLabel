<footer class="footer-section">
  <nav class="footer-section-menu">
    <a href="{{ route('home') }}" class="footer-item {{ request()->is('home') ? 'active' : '' }}">{{ __('messages.page.home') }}</a>
    <a href="{{ route('collection') }}" class="footer-item {{ request()->is('collection') ? 'active' : '' }}">{{ __('messages.page.collection') }}</a>
    <a href="{{ route('about') }}" class="footer-item {{ request()->is('about') ? 'active' : '' }}">{{ __('messages.page.about') }}</a>
    <a href="#">{{ __('messages.page.contact') }}</a>
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
