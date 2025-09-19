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

        // Disable auth biar langsung bisa POST/PUT/DELETE
        $this->withoutMiddleware(Authenticate::class);
    }

    /* ============================================================
     * CREATE
     * ============================================================*/
    /** @test */
    public function create_kategori_sukses()
    {
        $payload = ['nama_kategori' => 'Hijab Pashmina'];

        $resp = $this->post(route('kategori.store'), $payload);

        $resp->assertRedirect(route('kategori.index'));
        $resp->assertSessionHas('success', 'Kategori berhasil ditambahkan!');
        $this->assertDatabaseHas('m_kategori', ['nama_kategori' => 'Hijab Pashmina']);
    }

    /** @test */
    public function create_kategori_gagal_ketika_nama_kosong()
    {
        $payload = ['nama_kategori' => ''];

        $resp = $this->post(route('kategori.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nama_kategori']);
        $this->assertDatabaseMissing('m_kategori', ['nama_kategori' => '']);
    }

    /** @test */
    public function create_kategori_gagal_ketika_nama_terlalu_panjang()
    {
        $payload = ['nama_kategori' => str_repeat('a', 256)];

        $resp = $this->post(route('kategori.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nama_kategori']);
        $this->assertDatabaseMissing('m_kategori', ['nama_kategori' => str_repeat('a', 256)]);
    }

    /* ============================================================
     * READ
     * ============================================================*/
    /** @test */
    public function index_kategori_menampilkan_data()
    {
        KategoriModel::factory()->count(3)->create();

        $resp = $this->get(route('kategori.index'));

        $resp->assertStatus(200);
        $resp->assertViewIs('admin.kategori.index'); // fix view
        $resp->assertViewHas('kategori');
    }

    /** @test */
    public function show_kategori_menampilkan_detail()
    {
        $kategori = KategoriModel::factory()->create();

        // paksa request AJAX
        $resp = $this->get(route('kategori.show', $kategori->kategori_id), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $resp->assertStatus(200);
        $resp->assertViewIs('admin.kategori.show');
        $resp->assertViewHas('kategori', fn ($k) => $k->kategori_id === $kategori->kategori_id);
    }

    /** @test */
    public function show_kategori_gagal_ketika_kategori_tidak_ada()
    {
        $resp = $this->get(route('kategori.show', 999999), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $resp->assertStatus(404);
    }

    /* ============================================================
     * UPDATE
     * ============================================================*/
    /** @test */
    public function update_kategori_sukses()
    {
        $kategori = KategoriModel::factory()->create();

        $payload = ['nama_kategori' => 'Hijab Pashmina Updated'];

        $resp = $this->putJson(route('kategori.update', $kategori->kategori_id), $payload);

        $resp->assertStatus(200);
        $resp->assertJson([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui',
        ]);

        $this->assertDatabaseHas('m_kategori', [
            'kategori_id'   => $kategori->kategori_id,
            'nama_kategori' => 'Hijab Pashmina Updated',
        ]);
    }

    /** @test */
    public function update_kategori_gagal_ketika_nama_kosong()
    {
        $kategori = KategoriModel::factory()->create();

        $payload = ['nama_kategori' => ''];

        $resp = $this->putJson(route('kategori.update', $kategori->kategori_id), $payload);

        $resp->assertStatus(422); // Laravel validation error JSON
        $resp->assertJsonValidationErrors(['nama_kategori']);
        $this->assertDatabaseMissing('m_kategori', [
            'kategori_id'   => $kategori->kategori_id,
            'nama_kategori' => '',
        ]);
    }

    /** @test */
    public function update_kategori_gagal_ketika_kategori_tidak_ada()
    {
        $payload = ['nama_kategori' => 'Kategori Update Gagal'];

        $resp = $this->putJson(route('kategori.update', 999999), $payload);

        $resp->assertStatus(404);
    }

    /* ============================================================
     * DELETE
     * ============================================================*/
    /** @test */
    public function delete_kategori_sukses()
    {
        $kategori = KategoriModel::factory()->create();

        $resp = $this->deleteJson(route('kategori.destroy', $kategori->kategori_id));

        $resp->assertStatus(200);
        $resp->assertJson([
            'success' => true,
            'message' => 'Kategori berhasil dihapus',
        ]);

        $this->assertDatabaseMissing('m_kategori', ['kategori_id' => $kategori->kategori_id]);
    }

    /** @test */
    public function delete_kategori_gagal_ketika_kategori_tidak_ada()
    {
        $resp = $this->deleteJson(route('kategori.destroy', 999999));

        $resp->assertStatus(404);
    }
}
