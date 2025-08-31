<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\KategoriModel;
use App\Http\Middleware\Authenticate;

class KategoriTest extends TestCase
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
    public function create_kategori_sukses()
    {
        // Payload yang dikirim ke controller kategori.store
        $payload = [
            'nama_kategori' => 'Hijab Pashmina',
        ];

        // Act → request create kategori
        $resp = $this->post(route('kategori.store'), $payload);

        // Assert → redirect sukses dan ada flash message
        $resp->assertRedirect(route('kategori.index'));
        $resp->assertSessionHas('success', 'Kategori berhasil ditambahkan!');

        // Assert → cek kategori tersimpan di DB
        $this->assertDatabaseHas('m_kategori', [
            'nama_kategori' => 'Hijab Pashmina',
        ]);
    }

    /** @test */
    public function create_kategori_gagal_ketika_nama_kosong()
    {
        // Payload yang dikirim ke controller kategori.store
        $payload = [
            'nama_kategori' => '', // kosong
        ];

        // Act → request create kategori
        $resp = $this->post(route('kategori.store'), $payload);

        // Assert → redirect kembali ke form dengan error validasi
        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nama_kategori']);

        // Assert → cek kategori TIDAK tersimpan di DB
        $this->assertDatabaseMissing('m_kategori', [
            'nama_kategori' => '',
        ]);
    }

    /** @test */
    public function create_kategori_gagal_ketika_nama_terlalu_panjang()
    {
        // Payload yang dikirim ke controller kategori.store
        $payload = [
            'nama_kategori' => str_repeat('a', 256), // lebih dari 255 karakter
        ];

        // Act → request create kategori
        $resp = $this->post(route('kategori.store'), $payload);

        // Assert → redirect kembali ke form dengan error validasi
        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nama_kategori']);

        // Assert → cek kategori TIDAK tersimpan di DB
        $this->assertDatabaseMissing('m_kategori', [
            'nama_kategori' => str_repeat('a', 256),
        ]);
    }

    /* ============================================================
     * READ (INDEX + SHOW)
     * ============================================================*/

    /** @test */
    public function index_kategori_menampilkan_data()
    {
        KategoriModel::factory()->count(3)->create();

        $resp = $this->get(route('kategori.index'));

        $resp->assertStatus(200);
        $resp->assertViewIs('kategori.index');
        $resp->assertViewHas('kategori');
    }

    /** @test */
    public function show_kategori_menampilkan_detail()
    {
        $kategori = KategoriModel::factory()->create();

        $resp = $this->get(route('kategori.show', $kategori->kategori_id));

        $resp->assertStatus(200);
        $resp->assertViewIs('kategori.show');
        $resp->assertViewHas('kategori', fn ($k) => $k->kategori_id === $kategori->kategori_id);
    }

    /* ============================================================
     * UPDATE
     * ============================================================*/

    /** @test */
    public function update_kategori_sukses()
    {
        $kategori = KategoriModel::factory()->create();

        $payload = [
            'nama_kategori' => 'Hijab Pashmina Updated',
        ];

        $resp = $this->put(route('kategori.update', $kategori->kategori_id), $payload);

        $resp->assertRedirect(route('kategori.index'));
        $resp->assertSessionHas('success', 'Kategori berhasil diperbarui!');
        $this->assertDatabaseHas('m_kategori', [
            'nama_kategori' => 'Hijab Pashmina Updated',
            'kategori_id' => $kategori->kategori_id
        ]);
    }

    /** @test */
    public function update_kategori_gagal_ketika_nama_kosong()
    {
        $kategori = KategoriModel::factory()->create();

        $payload = [
            'nama_kategori' => '',
        ];

        $resp = $this->put(route('kategori.update', $kategori->kategori_id), $payload);

        $resp->assertSessionHasErrors(['nama_kategori']);
        $this->assertDatabaseMissing('m_kategori', [
            'nama_kategori' => '',
            'kategori_id' => $kategori->kategori_id
        ]);
    }

    /* ============================================================
     * DELETE
     * ============================================================*/

    /** @test */
    public function delete_kategori_sukses()
    {
        $kategori = KategoriModel::factory()->create();

        $resp = $this->delete(route('kategori.destroy', $kategori->kategori_id));

        $resp->assertRedirect(route('kategori.index'));
        $resp->assertSessionHas('success', 'Kategori berhasil dihapus!');
        $this->assertDatabaseMissing('m_kategori', ['kategori_id' => $kategori->kategori_id]);
    }

    /** @test */
    public function delete_kategori_gagal_ketika_kategori_tidak_ada()
    {
        $kategori_id_tidak_ada = 999999;

        $resp = $this->delete(route('kategori.destroy', $kategori_id_tidak_ada));

        $resp->assertStatus(404); // Not found
        $this->assertDatabaseMissing('m_kategori', ['kategori_id' => $kategori_id_tidak_ada]);
    }

    /** @test */
    public function update_kategori_gagal_ketika_kategori_tidak_ada()
    {
        $kategori_id_tidak_ada = 999999;

        $payload = [
            'nama_kategori' => 'Kategori Update Gagal',
        ];

        $resp = $this->put(route('kategori.update', $kategori_id_tidak_ada), $payload);

        $resp->assertStatus(404); // Not found
    }

    /** @test */
    public function show_kategori_gagal_ketika_kategori_tidak_ada()
    {
        $kategori_id_tidak_ada = 999999;

        $resp = $this->get(route('kategori.show', $kategori_id_tidak_ada));

        $resp->assertStatus(404); // Not found
    }
}
