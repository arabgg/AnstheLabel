<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    @stack('head')
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('admin.layouts.sidebar')

        {{-- Konten Utama --}}
        <div x-data="{ sidebarOpen: true }"
             :class="sidebarOpen ? 'ml-64' : 'ml-16'"
             class="flex-1 transition-all duration-300 p-4">
            <main class="w-full mx-auto">
                @yield('content')
            </main>

            {{-- Footer --}}
            @include('admin.layouts.footer')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
