<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body class="bg-gray-00 min-h-screen flex flex-col">

    {{-- Navbar Mobile --}}
    @include('admin.layouts.navbar-mobile')
    
    {{-- Sidebar + Main + Footer --}}
    <div x-data="{ sidebarOpen: true }" class="flex">

        {{-- Sidebar Desktop --}}
        @include('admin.layouts.sidebar')

        {{-- Main Section + Footer --}}
        <div :class="sidebarOpen ? 'ml-64' : 'ml-16'" class="flex-1 flex flex-col transition-all duration-300 min-h-screen">

            {{-- Main Content --}}
            <main class="flex-1 p-2">
                @yield('content')
            </main>

            {{-- Footer --}}
            @include('admin.layouts.footer')
        </div>
    </div>

    @stack('scripts')
</body>
</html>