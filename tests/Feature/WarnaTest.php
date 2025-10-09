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
        $this->withoutMiddleware(Authenticate::class);
    }

    /* ============================================================
     * CREATE
     * ============================================================*/

    /** @test */
    public function create_warna_sukses()
    {
        $payload = [
            'kode_hex' => '#FF5733',
            'nama_warna' => 'Merah Orange',
        ];

        $resp = $this->post(route('warna.store'), $payload);

        $resp->assertRedirect(route('warna.index'));
        $resp->assertSessionHas('success', 'Warna berhasil ditambahkan!');

        $this->assertDatabaseHas('m_warna', [
            'kode_hex' => '#FF5733',
            'nama_warna' => 'Merah Orange',
        ]);
    }

    /** @test */
    public function create_warna_gagal_ketika_kode_hex_kosong()
    {
        $payload = [
            'kode_hex' => '',
            'nama_warna' => 'Merah Orange',
        ];

        $resp = $this->post(route('warna.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['kode_hex']);
        $this->assertDatabaseMissing('m_warna', [
            'nama_warna' => 'Merah Orange',
        ]);
    }

    /** @test */
    public function create_warna_gagal_ketika_nama_warna_kosong()
    {
        $payload = [
            'kode_hex' => '#FF5733',
            'nama_warna' => '',
        ];

        $resp = $this->post(route('warna.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nama_warna']);
        $this->assertDatabaseMissing('m_warna', [
            'kode_hex' => '#FF5733',
        ]);
    }

    /** @test */
    public function create_warna_gagal_ketika_kode_hex_terlalu_panjang()
    {
        $payload = [
            'kode_hex' => '#FF5733FF', // lebih dari 7 karakter
            'nama_warna' => 'Merah Orange',
        ];

        $resp = $this->post(route('warna.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['kode_hex']);
        $this->assertDatabaseMissing('m_warna', [
            'nama_warna' => 'Merah Orange',
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
        $resp->assertViewIs('admin.warna.index'); // disesuaikan
        $resp->assertViewHas('warna');
    }

    /** @test */
    public function show_warna_menampilkan_detail_dengan_ajax()
    {
        $warna = WarnaModel::factory()->create();

        $resp = $this->get(route('warna.show', $warna->warna_id), [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ]);

        $resp->assertStatus(200);
        $resp->assertViewIs('admin.warna.show');
        $resp->assertViewHas('warna', fn ($w) => $w->warna_id === $warna->warna_id);
    }

    /** @test */
    public function show_warna_redirect_ke_index_ketika_non_ajax()
    {
        $warna = WarnaModel::factory()->create();

        $resp = $this->get(route('warna.show', $warna->warna_id));

        $resp->assertRedirect(route('warna.index'));
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

        $resp->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Warna berhasil diperbarui',
            ]);

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

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['kode_hex']);
        $this->assertDatabaseMissing('m_warna', [
            'kode_hex' => '',
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

        $resp->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Warna berhasil dihapus',
                'id' => $warna->warna_id
            ]);

        $this->assertDatabaseMissing('m_warna', ['warna_id' => $warna->warna_id]);
    }

    /** @test */
    public function delete_warna_gagal_ketika_warna_tidak_ada()
    {
        $resp = $this->delete(route('warna.destroy', 999999));

        $resp->assertStatus(404);
    }
}
