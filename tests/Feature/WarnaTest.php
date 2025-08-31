<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\WarnaModel;
use App\Http\Middleware\Authenticate;

class WarnaTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable middleware auth supaya POST bisa langsung di-test
        $this->withoutMiddleware(Authenticate::class);
    }

    /* ============================================================
     * CREATE
     * ============================================================*/

    /** @test */
    public function create_warna_sukses()
    {
        // Payload yang dikirim ke controller warna.store
        $payload = [
            'kode_hex' => '#FF5733',
            'nama_warna' => 'Merah Orange',
        ];

        // Act → request create warna
        $resp = $this->post(route('warna.store'), $payload);

        // Assert → redirect sukses dan ada flash message
        $resp->assertRedirect(route('warna.index'));
        $resp->assertSessionHas('success', 'Warna berhasil ditambahkan!');

        // Assert → cek warna tersimpan di DB
        $this->assertDatabaseHas('m_warna', [
            'kode_hex' => '#FF5733',
            'nama_warna' => 'Merah Orange',
        ]);
    }

    /** @test */
    public function create_warna_gagal_ketika_kode_hex_kosong()
    {
        // Payload yang dikirim ke controller warna.store
        $payload = [
            'kode_hex' => '', // kosong
            'nama_warna' => 'Merah Orange',
        ];

        // Act → request create warna
        $resp = $this->post(route('warna.store'), $payload);

        // Assert → redirect kembali ke form dengan error validasi
        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['kode_hex']);

        // Assert → cek warna TIDAK tersimpan di DB
        $this->assertDatabaseMissing('m_warna', [
            'nama_warna' => 'Merah Orange',
        ]);
    }

    /** @test */
    public function create_warna_gagal_ketika_nama_warna_kosong()
    {
        // Payload yang dikirim ke controller warna.store
        $payload = [
            'kode_hex' => '#FF5733',
            'nama_warna' => '', // kosong
        ];

        // Act → request create warna
        $resp = $this->post(route('warna.store'), $payload);

        // Assert → redirect kembali ke form dengan error validasi
        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nama_warna']);

        // Assert → cek warna TIDAK tersimpan di DB
        $this->assertDatabaseMissing('m_warna', [
            'kode_hex' => '#FF5733',
        ]);
    }

    /** @test */
    public function create_warna_gagal_ketika_kode_hex_terlalu_panjang()
    {
        // Payload yang dikirim ke controller warna.store
        $payload = [
            'kode_hex' => '#FF573333', // lebih dari 7 karakter
            'nama_warna' => 'Merah Orange',
        ];

        // Act → request create warna
        $resp = $this->post(route('warna.store'), $payload);

        // Assert → redirect kembali ke form dengan error validasi
        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['kode_hex']);

        // Assert → cek warna TIDAK tersimpan di DB
        $this->assertDatabaseMissing('m_warna', [
            'kode_hex' => '#FF573333',
        ]);
    }

    /** @test */
    public function create_warna_gagal_ketika_nama_warna_terlalu_panjang()
    {
        // Payload yang dikirim ke controller warna.store
        $payload = [
            'kode_hex' => '#FF5733',
            'nama_warna' => str_repeat('a', 256), // lebih dari 255 karakter
        ];

        // Act → request create warna
        $resp = $this->post(route('warna.store'), $payload);

        // Assert → redirect kembali ke form dengan error validasi
        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nama_warna']);

        // Assert → cek warna TIDAK tersimpan di DB
        $this->assertDatabaseMissing('m_warna', [
            'kode_hex' => '#FF5733',
        ]);
    }

    /* ============================================================
     * READ (INDEX + SHOW)
     * ============================================================*/

    /** @test */
    public function index_warna_menampilkan_data()
    {
        WarnaModel::factory()->count(3)->create();

        $resp = $this->get(route('warna.index'));

        $resp->assertStatus(200);
        $resp->assertViewIs('warna.index');
        $resp->assertViewHas('warna');
    }

    /** @test */
    public function show_warna_menampilkan_detail()
    {
        $warna = WarnaModel::factory()->create();

        $resp = $this->get(route('warna.show', $warna->warna_id));

        $resp->assertStatus(200);
        $resp->assertViewIs('warna.show');
        $resp->assertViewHas('warna', fn ($w) => $w->warna_id === $warna->warna_id);
    }

    /* ============================================================
     * UPDATE
     * ============================================================*/

    /** @test */
    public function update_warna_sukses()
    {
        $warna = WarnaModel::factory()->create();

        $payload = [
            'kode_hex' => '#00FF00',
            'nama_warna' => 'Hijau Updated',
        ];

        $resp = $this->put(route('warna.update', $warna->warna_id), $payload);

        $resp->assertRedirect(route('warna.index'));
        $resp->assertSessionHas('success', 'Warna berhasil diperbarui!');
        $this->assertDatabaseHas('m_warna', [
            'kode_hex' => '#00FF00',
            'nama_warna' => 'Hijau Updated',
            'warna_id' => $warna->warna_id
        ]);
    }

    /** @test */
    public function update_warna_gagal_ketika_kode_hex_kosong()
    {
        $warna = WarnaModel::factory()->create();

        $payload = [
            'kode_hex' => '',
            'nama_warna' => 'Hijau Updated',
        ];

        $resp = $this->put(route('warna.update', $warna->warna_id), $payload);

        $resp->assertSessionHasErrors(['kode_hex']);
        $this->assertDatabaseMissing('m_warna', [
            'kode_hex' => '',
            'warna_id' => $warna->warna_id
        ]);
    }

    /** @test */
    public function update_warna_gagal_ketika_nama_warna_kosong()
    {
        $warna = WarnaModel::factory()->create();

        $payload = [
            'kode_hex' => '#00FF00',
            'nama_warna' => '',
        ];

        $resp = $this->put(route('warna.update', $warna->warna_id), $payload);

        $resp->assertSessionHasErrors(['nama_warna']);
        $this->assertDatabaseMissing('m_warna', [
            'nama_warna' => '',
            'warna_id' => $warna->warna_id
        ]);
    }

    /* ============================================================
     * DELETE
     * ============================================================*/

    /** @test */
    public function delete_warna_sukses()
    {
        $warna = WarnaModel::factory()->create();

        $resp = $this->delete(route('warna.destroy', $warna->warna_id));

        $resp->assertRedirect(route('warna.index'));
        $resp->assertSessionHas('success', 'Warna berhasil dihapus!');
        $this->assertDatabaseMissing('m_warna', ['warna_id' => $warna->warna_id]);
    }

    /** @test */
    public function delete_warna_gagal_ketika_warna_tidak_ada()
    {
        $warna_id_tidak_ada = 999999;

        $resp = $this->delete(route('warna.destroy', $warna_id_tidak_ada));

        $resp->assertStatus(404); // Not found
        $this->assertDatabaseMissing('m_warna', ['warna_id' => $warna_id_tidak_ada]);
    }

    /** @test */
    public function update_warna_gagal_ketika_warna_tidak_ada()
    {
        $warna_id_tidak_ada = 999999;

        $payload = [
            'kode_hex' => '#000000',
            'nama_warna' => 'Warna Update Gagal',
        ];

        $resp = $this->put(route('warna.update', $warna_id_tidak_ada), $payload);

        $resp->assertStatus(404); // Not found
    }

    /** @test */
    public function show_warna_gagal_ketika_warna_tidak_ada()
    {
        $warna_id_tidak_ada = 999999;

        $resp = $this->get(route('warna.show', $warna_id_tidak_ada));

        $resp->assertStatus(404); // Not found
    }

    /** @test */
    public function create_warna_gagal_ketika_kode_hex_format_salah()
    {
        // Payload yang dikirim ke controller warna.store
        $payload = [
            'kode_hex' => 'FF5733', // tanpa # (format salah)
            'nama_warna' => 'Merah Orange',
        ];

        // Act → request create warna
        $resp = $this->post(route('warna.store'), $payload);

        // Assert → redirect kembali ke form dengan error validasi
        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['kode_hex']);

        // Assert → cek warna TIDAK tersimpan di DB
        $this->assertDatabaseMissing('m_warna', [
            'nama_warna' => 'Merah Orange',
        ]);
    }
}
