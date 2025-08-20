<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    @stack('head')
</head>

<body class="bg-gray-00 min-h-screen flex flex-col">

    {{-- Navbar Mobile --}}
    @include('admin.layouts.navbar-mobile')
    
    <div class="flex">
        {{-- Sidebar Desktop --}}
        @include('admin.layouts.sidebar')

        {{-- Konten Utama --}}
        <main class="flex-1 p-2">
            @yield('content')
        </main>
    </div>

    {{-- Footer --}}
    @include('admin.layouts.footer')

    @stack('scripts')
</body>

</html>
