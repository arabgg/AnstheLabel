<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ansthelabel</title>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    {{-- Font --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    
    @include('home.layouts.header')

    {{-- Breadcrumb --}}
    <div class="container">
        @yield('breadcrumb')
    </div>

    {{-- Content Halaman --}}
    @yield('content')

    @include('home.layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Loading Screen
        document.addEventListener("DOMContentLoaded", function () {
            const skeletonWrappers = document.querySelectorAll('.skeleton-wrapper');

            skeletonWrappers.forEach(wrapper => {
                const parent = wrapper.parentElement;
                const target = parent.querySelector('.skeleton-target');
                const images = parent.querySelectorAll('img');

                if (!images.length) {
                    wrapper.style.display = "none";
                    if (target) target.style.display = "block";
                    return;
                }

                let loadedCount = 0;
                const checkLoaded = () => {
                    loadedCount++;
                    if (loadedCount === images.length) {
                        wrapper.style.display = "none";
                        if (target) target.style.display = "block";
                    }
                };

                images.forEach(img => {
                    if (img.complete) {
                        checkLoaded();
                    } else {
                        img.addEventListener('load', checkLoaded);
                        img.addEventListener('error', checkLoaded);
                    }
                });
            });
        });

        // Header Scroll Effect
        let lastScrollTop = 0;
        const header = document.getElementById("mainHeader");

        window.addEventListener("scroll", function () {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > lastScrollTop) {
                header.classList.add("hidden");
            } else {
                header.classList.remove("hidden");
            }

            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });
    </script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            slidesPerView: 'auto',         
            centeredSlides: true,          
            spaceBetween: 0,               
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/6894156ca4fc79192a7bc3ca/1j2177ci1';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->

    @stack('scripts')
</body>
</html>
