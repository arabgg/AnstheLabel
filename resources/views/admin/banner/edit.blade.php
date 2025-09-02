<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">Edit Banner</h2>

    <form id="editBannerForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-5">
            @if($banner->foto_banner)
                @if(pathinfo($banner->foto_banner, PATHINFO_EXTENSION) === 'mp4')
                    <video class="w-full h-44 object-cover mb-2 rounded-lg" controls>
                        <source src="{{ asset('storage/banner/' . $banner->foto_banner) }}" type="video/mp4">
                    </video>
                @else
                    <img src="{{ asset('storage/banner/' . $banner->foto_banner) }}" alt="Banner" class="w-full h-44 rounded-lg object-cover mb-2">
                @endif
            @endif
            <input type="file" name="foto_banner" class="w-full border p-2 rounded-lg file:rounded-lg" required>
        </div>

        <div class="flex justify-end gap-2">
            <button type="button" class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600" onclick="closeModal()">Batal</button>
            <button type="submit" class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600">Simpan</button>
        </div>
    </form>
</div>

<script>
    const form = document.getElementById('editBannerForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const url = '{{ route("banner.update", $banner->banner_id) }}';
        const formData = new FormData(this);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PUT',
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                Swal.fire({
                    icon: 'success',
                    title: data.message,
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
                // Update gambar di tabel jika perlu
                document.querySelector(`[data-banner-id='{{ $banner->banner_id }}'] img`)?.setAttribute('src', data.foto_banner);
                closeModal();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal memperbarui banner',
                    toast: true,
                    position: 'top-end'
                });
            }
        })
        .catch(err => Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan',
            toast: true,
            position: 'top-end'
        }));
    });
</script>
