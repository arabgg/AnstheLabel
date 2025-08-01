<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Page')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('head')
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    {{-- navbar --}}
    <nav class="bg-white px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <a href="/">
                    <img src="{{ asset('storage/images/ansthelabel.png') }}" class="h-8" alt="AnstheLabel Logo">
                </a>
                <a href="/produk" class="text-black-600 hover:text-blue-500">Produk</a>
            </div>
            <div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition duration-200">
                        Logout
                    </button>
                </form>
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
        &copy; {{ date('Y') }} Admin System. All rights reserved.
    </footer>

    @stack('scripts')
</body>

</html>
