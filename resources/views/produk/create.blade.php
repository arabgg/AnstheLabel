@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Tambah Produk</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-600 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md space-y-6">
        @csrf

        {{-- Foto Utama --}}
        <div>
            <label class="block font-medium mb-1">Foto Utama</label>
            <input type="file" id="foto-utama" accept="image/*" class="hidden">
            <label for="foto-utama" class="w-60 aspect-[4/5] border-2 border-dashed flex items-center justify-center cursor-pointer rounded">
                <img id="preview-utama" src="https://via.placeholder.com/200?text=+" class="object-cover w-full h-full" alt="Preview Utama">
            </label>
            <input type="hidden" name="foto_utama" id="foto-utama-hidden">
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
    </form>
</div>

{{-- Modal Crop --}}
<div id="crop-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white p-4 rounded shadow-lg">
        <div class="w-[400px] h-[300px]">
            <img id="image-to-crop" class="max-w-full" />
        </div>
        <div class="flex justify-end gap-2 mt-4">
            <button id="cancel-crop" class="px-4 py-2 bg-gray-400 text-white rounded">Batal</button>
            <button id="save-crop" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
        </div>
    </div>
</div>

{{-- Cropper.js CSS & JS --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
let cropper;
const fotoUtamaInput = document.getElementById('foto-utama');
const cropModal = document.getElementById('crop-modal');
const imageToCrop = document.getElementById('image-to-crop');
const previewUtama = document.getElementById('preview-utama');
const fotoUtamaHidden = document.getElementById('foto-utama-hidden');

// Saat pilih gambar
fotoUtamaInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(event) {
        imageToCrop.src = event.target.result;
        cropModal.classList.remove('hidden');
        cropModal.classList.add('flex');

        // Hancurkan cropper sebelumnya jika ada
        if (cropper) {
            cropper.destroy();
        }

        // Inisialisasi cropper dengan rasio 3:4
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 4 / 5,
            viewMode: 1,
            autoCropArea: 1
        });
    }
    reader.readAsDataURL(file);
});

// Simpan hasil crop
document.getElementById('save-crop').addEventListener('click', function() {
    const canvas = cropper.getCroppedCanvas({
        width: 600, // resolusi akhir
        height: 800
    });

    // Tampilkan preview
    previewUtama.src = canvas.toDataURL('image/jpeg');

    // Simpan base64 ke input hidden
    fotoUtamaHidden.value = canvas.toDataURL('image/jpeg');

    // Tutup modal
    cropModal.classList.add('hidden');
    cropModal.classList.remove('flex');

    cropper.destroy();
});

// Batal crop
document.getElementById('cancel-crop').addEventListener('click', function() {
    cropModal.classList.add('hidden');
    cropModal.classList.remove('flex');
    cropper.destroy();
});
</script>
@endsection
