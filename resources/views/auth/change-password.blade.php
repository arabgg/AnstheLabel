@extends('admin.layouts.app')

@section('content')
    <div class="container bg-white rounded-lg min-h-screen">
        <div class="p-8 bg-white rounded-lg shadow">
            {{-- Judul --}}
            <div class="flex justify-between items-start mb-7 border-b border-gray-300 pb-4">
                <h1 class="text-2xl font-bold pl-4 pt-4">Kelola Kata Sandi</h1>
            </div>
            <div class="max-w-lg mx-auto">

                {{-- Pesan error (fallback kalau ada error validasi) --}}
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="changePasswordForm" method="POST" action="{{ route('auth.change-password.update') }}">
                    @csrf

                    {{-- Kata Sandi Lama --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold">Masukkan Kata Sandi Lama</label>
                        <input type="password" name="current_password"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-rose-700 font-sans"
                            placeholder="••••••••" required>
                    </div>

                    {{-- Kata Sandi Baru --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold">Masukkan Kata Sandi Baru</label>
                        <input type="password" name="new_password"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-rose-700 font-sans"
                            placeholder="••••••••" required>
                    </div>

                    {{-- Konfirmasi Kata Sandi Baru --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="new_password_confirmation"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-rose-700 font-sans"
                            placeholder="••••••••" required>
                    </div>

                    {{-- Tombol Submit --}}
                    <button type="submit"
                        class="w-full bg-[#560024] text-white py-2 rounded-lg hover:bg-[#7a0033] transition">
                        Simpan Kata Sandi
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Konfirmasi sebelum submit
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault(); // stop submit dulu
            Swal.fire({
                title: 'Yakin ganti password?',
                text: "Password lama akan diganti dengan yang baru",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#560024',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, ganti!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit(); // lanjut submit form
                }
            });
        });

        // Alert berhasil kalau ada session sukses
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#560024'
            });
        @endif
    </script>
@endpush
