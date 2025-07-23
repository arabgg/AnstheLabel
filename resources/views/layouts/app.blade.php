<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ansthelabel</title>

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bestproduk.css') }}">
    <link rel="stylesheet" href="{{ asset('css/collection.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    {{-- Font --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('layouts.header')

    {{-- Breadcrumb --}}
    <div class="container">
        @yield('breadcrumb')
    </div>

    {{-- Content Halaman --}}
    @yield('content')

    @include('layouts.footer')

    @stack('scripts')
</body>
</html>
