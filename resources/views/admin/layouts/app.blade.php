<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Page')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
    <!-- Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>


    @stack('head')
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    {{-- navbar --}}
    <nav class="bg-white px-6 py-4 shadow">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            {{-- Kiri: Logo dan Menu --}}
            <div class="flex items-center space-x-6">
                <a href="/">
                    <img src="{{ asset('storage/images/ansthelabel.png') }}" class="h-10" alt="AnstheLabel Logo">
                </a>
            </div>

            {{-- Kanan: Dropdown User --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{-- Ikon user --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 hover:text-blue-600"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A8.966 8.966 0 0012 21c2.485 0 4.735-.998 6.364-2.617M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>

                {{-- Dropdown menu --}}
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg py-1 z-50">
                    <a href="{{ url('/user/change-password') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ganti Password</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>


    {{-- konten utama --}}
    <main class="flex-grow container mx-auto px-4 py-6">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- footer --}}
    <footer class="bg-white py-4 text-center text-sm text-gray-500 shadow-inner">
        &copy; {{ date('Y') }} Copyright by Ansthelabel.
    </footer>

    @stack('scripts')
</body>

</html>
