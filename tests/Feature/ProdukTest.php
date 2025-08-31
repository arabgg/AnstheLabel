<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;
use App\Models\KategoriModel;
use App\Models\BahanModel;
use App\Models\UkuranModel;
use App\Models\WarnaModel;
use App\Http\Middleware\Authenticate;
use App\Models\ProdukModel;

class ProdukTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable middleware auth supaya POST bisa langsung di-test
        $this->withoutMiddleware(Authenticate::class);

        // Pastikan folder untuk foto ada
        File::ensureDirectoryExists(public_path('storage/foto_produk'));
    }

    /* ============================================================
     * CREATE
     * ============================================================*/

    /** @test */
    public function create_produk_minimal_fields_sukses()
    {
        // Arrange → buat data relasi foreign key
        $kategori = KategoriModel::factory()->create();
        $bahan    = BahanModel::factory()->create();
        $ukuran   = UkuranModel::factory()->create();
        $warna    = WarnaModel::factory()->create();

        // Payload yang dikirim ke controller produk.store
        $payload = [
            'nama_produk' => 'Hijab Pashmina Premium',
            'deskripsi'   => 'Nyaman dipakai harian.',
            'harga'       => '120000', // string supaya test casting jalan
            'diskon'      => null,
            'kategori_id' => $kategori->kategori_id,
            'bahan_id'    => $bahan->bahan_id,
            'ukuran_id'   => [(string)$ukuran->ukuran_id], // array of string id
            'warna_id'    => [(string)$warna->warna_id],   // array of string id
            'foto_utama'  => UploadedFile::fake()->image('utama.jpg', 800, 800),
        ];

        // Act → request create produk
        $resp = $this->post(route('produk.store'), $payload);

        // Assert → redirect sukses dan ada flash message
        $resp->assertRedirect(route('produk.index'));
        $resp->assertSessionHas('success', 'Produk berhasil disimpan!');

        // Assert → cek produk tersimpan di DB
        $this->assertDatabaseHas('t_produk', [
            'nama_produk' => 'Hijab Pashmina Premium',
            'kategori_id' => $kategori->kategori_id,
            'bahan_id'    => $bahan->bahan_id,
        ]);
    }

    /** @test */
    public function create_produk_gagal_ketika_nama_kosong()
    {
        // Arrange → buat data relasi foreign key
        $kategori = KategoriModel::factory()->create();
        $bahan    = BahanModel::factory()->create();
        $ukuran   = UkuranModel::factory()->create();
        $warna    = WarnaModel::factory()->create();

        // Payload yang dikirim ke controller produk.store
        $payload = [
            'nama_produk' => '', // kosong
            'deskripsi'   => 'Nyaman dipakai harian.',
            'harga'       => '120000', // string supaya test casting jalan
            'diskon'      => null,
            'kategori_id' => $kategori->kategori_id,
            'bahan_id'    => $bahan->bahan_id,
            'ukuran_id'   => [(string)$ukuran->ukuran_id], // array of string id
            'warna_id'    => [(string)$warna->warna_id],   // array of string id
            'foto_utama'  => UploadedFile::fake()->image('utama.jpg', 800, 800),
        ];

        // Act → request create produk
        $resp = $this->post(route('produk.store'), $payload);

        // Assert → redirect kembali ke form dengan error validasi
        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nama_produk']);

        // Assert → cek produk TIDAK tersimpan di DB
        $this->assertDatabaseMissing('t_produk', [
            'deskripsi' => 'Nyaman dipakai harian.',
            'kategori_id' => $kategori->kategori_id,
            'bahan_id'    => $bahan->bahan_id,
        ]);
    }

    /** @test */
    public function create_produk_gagal_ketika_harga_bukan_angka()
    {
        // Arrange → buat data relasi foreign key
        $kategori = KategoriModel::factory()->create();
        $bahan    = BahanModel::factory()->create();
        $ukuran   = UkuranModel::factory()->create();
        $warna    = WarnaModel::factory()->create();

        // Payload yang dikirim ke controller produk.store
        $payload = [
            'nama_produk' => 'Hijab Pashmina Premium',
            'deskripsi'   => 'Nyaman dipakai harian.',
            'harga'       => 'seratus dua puluh ribu', // bukan angka
            'diskon'      => null,
            'kategori_id' => $kategori->kategori_id,
            'bahan_id'    => $bahan->bahan_id,
            'ukuran_id'   => [(string)$ukuran->ukuran_id], // array of string id
            'warna_id'    => [(string)$warna->warna_id],   // array of string id
            'foto_utama'  => UploadedFile::fake()->image('utama.jpg', 800, 800),
        ];

        // Act → request create produk
        $resp = $this->post(route('produk.store'), $payload);

        // Assert → redirect kembali ke form dengan error validasi
        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['harga']);

        // Assert → cek produk TIDAK tersimpan di DB
        $this->assertDatabaseMissing('t_produk', [
            'nama_produk' => 'Hijab Pashmina Premium',
            'deskripsi'   => 'Nyaman dipakai harian.',
            'kategori_id' => $kategori->kategori_id,
            'bahan_id'    => $bahan->bahan_id,
        ]);
    }

    /* ============================================================
     * READ (INDEX + SHOW)
     * ============================================================*/

    /** @test */
    public function index_produk_menampilkan_data()
    {
        ProdukModel::factory()->count(3)->create();

        $resp = $this->get(route('produk.index'));

        $resp->assertStatus(200);
        $resp->assertViewIs('produk.index'); // sesuaikan dengan view kamu
        $resp->assertViewHas('produk');
    }

    /** @test */
    public function show_produk_menampilkan_detail()
    {
        $produk = ProdukModel::factory()->create();

        $resp = $this->get(route('produk.index') . '/' . $produk->produk_id . '/show');

        $resp->assertStatus(200);
        $resp->assertViewIs('produk.show'); // sesuaikan dengan view kamu
        $resp->assertViewHas('produk', fn ($p) => $p->produk_id === $produk->produk_id);
    }

    /* ============================================================
     * UPDATE
     * ============================================================*/

    /** @test */
    public function update_produk_sukses()
    {
        $produk = ProdukModel::factory()->create();
        $ukuran = UkuranModel::factory()->create();
        $warna  = WarnaModel::factory()->create();

        $payload = [
            'nama_produk' => 'Hijab Pashmina Update',
            'deskripsi'   => 'Update sukses',
            'harga'       => '150000',
            'kategori_id' => $produk->kategori_id,
            'bahan_id'    => $produk->bahan_id,
            // tambahkan jika validasi butuh:
            'ukuran_id' => [(string)$ukuran->ukuran_id],
            'warna_id' => [(string)$warna->warna_id]
        ];

        $resp = $this->put(route('produk.update', $produk->produk_id), $payload);

        $resp->assertRedirect(route('produk.index'));
        $this->assertDatabaseHas('t_produk', ['nama_produk' => 'Hijab Pashmina Update', 'deskripsi' => 'Update sukses']);
    }

    /** @test */
    public function update_produk_gagal_ketika_nama_kosong()
    {
        $produk = ProdukModel::factory()->create();

        $payload = [
            'nama_produk' => '',
            'deskripsi'   => 'Update gagal',
            'harga'       => '150000',
            'kategori_id' => $produk->kategori_id,
            'bahan_id'    => $produk->bahan_id,
        ];

        $resp = $this->put(route('produk.update', $produk->produk_id), $payload);

        $resp->assertSessionHasErrors(['nama_produk']);
        $this->assertDatabaseMissing('t_produk', ['deskripsi' => 'Update gagal']);
    }

    /* ============================================================
     * DELETE
     * ============================================================*/

    /** @test */
    public function delete_produk_sukses()
    {
        $produk = ProdukModel::factory()->create();

        $resp = $this->delete(route('produk.destroy', $produk->produk_id));

        $resp->assertRedirect(route('produk.index'));
        $this->assertDatabaseMissing('t_produk', ['produk_id' => $produk->produk_id]);
    }

    /** @test */
    public function delete_produk_gagal_ketika_produk_tidak_ada()
    {
        $produk_id_tidak_ada = 999999;

        $resp = $this->delete(route('produk.destroy', $produk_id_tidak_ada));

        $resp->assertStatus(404); // Not found
        $this->assertDatabaseMissing('t_produk', ['produk_id' => $produk_id_tidak_ada]);
    }

    /** @test */
    public function update_produk_gagal_ketika_produk_tidak_ada()
    {
        $produk_id_tidak_ada = 999999;
        $ukuran = UkuranModel::factory()->create();
        $warna  = WarnaModel::factory()->create();

        $payload = [
            'nama_produk' => 'Hijab Pashmina Update',
            'deskripsi'   => 'Update gagal produk tidak ada',
            'harga'       => '150000',
            'kategori_id' => 1,
            'bahan_id'    => 1,
            'ukuran_id' => [(string)$ukuran->ukuran_id],
            'warna_id' => [(string)$warna->warna_id]
        ];

        $resp = $this->put(route('produk.update', $produk_id_tidak_ada), $payload);

        $resp->assertStatus(404); // Not found
    }

    /** @test */
    public function show_produk_gagal_ketika_produk_tidak_ada()
    {
        $produk_id_tidak_ada = 999999;

        $resp = $this->get("/produk/{$produk_id_tidak_ada}/show");

        $resp->assertStatus(404); // Not found
    }
}