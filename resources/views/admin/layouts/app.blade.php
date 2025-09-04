<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Ansthelabel</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-200 min-h-screen flex flex-col font-Montserrat">
    @include('admin.layouts.navbar-mobile')
    
    <div class="flex">
        @include('admin.layouts.sidebar') 
        
        <div class="flex-1 flex flex-col min-h-screen ml-60 ">
            <main class="flex-1 p-2">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
