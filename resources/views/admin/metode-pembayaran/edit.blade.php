@extends('admin.layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-xl">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Metode Pembayaran</h1>

        <form action="{{ route('metode_pembayaran.update', $mp->metode_pembayaran_id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Dropdown Metode --}}
            <div>
                <label for="metode_id" class="block text-sm font-medium text-gray-700 mb-1">Metode</label>
                <select name="metode_id" id="metode_id" required
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">-- Pilih Metode --</option>
                    @foreach ($metodes as $metode)
                        <option value="{{ $metode->metode_id }}"
                            {{ old('metode_id', $mp->metode_id) == $metode->metode_id ? 'selected' : '' }}>
                            {{ $metode->nama_metode }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Nama Pembayaran --}}
            <div>
                <label for="nama_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Nama Pembayaran</label>
                <input type="text" name="nama_pembayaran" id="nama_pembayaran"
                    value="{{ old('nama_pembayaran', $mp->nama_pembayaran) }}"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('nama_pembayaran')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Icon --}}
            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                @if ($mp->icon)
                    <div class="mb-2">
                        <img src="{{ asset('storage/icons/' . $mp->icon) }}" alt="Icon" class="h-12">
                    </div>
                @endif
                <input type="file" name="icon" id="icon"
                    class="w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-indigo-700 hover:file:bg-indigo-100">
                @error('icon')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kode Bayar --}}
            <div>
                <label for="kode_bayar" class="block text-sm font-medium text-gray-700 mb-1">Kode Bayar</label>
                <input type="text" name="kode_bayar" id="kode_bayar"
                    value="{{ old('kode_bayar', $mp->kode_bayar) }}"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('kode_bayar')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status_pembayaran" id="status_pembayaran"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="1" {{ old('status_pembayaran', $mp->status_pembayaran) == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status_pembayaran', $mp->status_pembayaran) == 0 ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center space-x-3 pt-4">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow-md transition duration-200">
                    Update
                </button>
                <a href="{{ route('metode_pembayaran.index') }}"
                    class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
