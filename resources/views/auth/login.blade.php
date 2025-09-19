<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ansthelabel</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="h-screen w-screen flex items-center justify-center">
    <div class="absolute inset-0 z-0"
        style="background: linear-gradient(to top right, #FFFFFF, #FBE9EB, #F4D6CC, #A65A6A, #560024);"></div>

    <div class="fixed inset-0 flex items-center justify-center z-10">
        <div
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-lg p-8 w-full max-w-md text-center">
            <img src="{{ asset('storage/page/ansthelabel.png') }}" alt="Ansthelabel" class="mx-auto mb-6 w-60">
            <form id="loginForm">
                @csrf
                <div class="mb-4 text-left">
                    <label for="username" class="block mb-2 text-small font-medium text-gray-900">Username</label>
                    <input type="text" id="username" name="username" placeholder="admin ansthelabel" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-rose-700 font-Montserrat">
                </div>

                <div class="mb-6 text-left">
                    <label for="password" class="block mb-2 text-small font-medium text-gray-900">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-rose-700 font-sans">
                </div>

                <button type="submit"
                    class="w-full bg-[#560024] text-white py-2 transition hover:bg-[#A65A6A] rounded">LOG
                    IN</button>

                <p id="loginError" class="text-red-600 mt-4 hidden"></p>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('login') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(json => {
                    if (json.status) {
                        // Login berhasil -> langsung redirect
                        window.location.href = json.redirect;
                    } else {
                        // Login gagal
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Gagal',
                            text: json.message || 'Username atau password salah'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan sistem.'
                    });
                    console.error(error);
                });
        });
    </script>

</body>

</html>
