<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\UkuranModel;
use App\Http\Middleware\Authenticate;

class UkuranTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable middleware auth supaya request bisa langsung di-test
        $this->withoutMiddleware(Authenticate::class);
    }

    /* ============================================================
     * CREATE
     * ============================================================*/

    /** @test */
    public function create_ukuran_sukses()
    {
        $payload = [
            'nama_ukuran' => 'XL',
            'deskripsi'   => 'Ukuran Extra Large',
        ];

        $resp = $this->post(route('ukuran.store'), $payload);

        $resp->assertRedirect(route('ukuran.index'));
        $resp->assertSessionHas('success', 'Ukuran berhasil ditambahkan!');

        $this->assertDatabaseHas('m_ukuran', [
            'nama_ukuran' => 'XL',
            'deskripsi'   => 'Ukuran Extra Large',
        ]);
    }

    /** @test */
    public function create_ukuran_gagal_ketika_tanpa_deskripsi()
    {
        $payload = [
            'nama_ukuran' => 'XXXL',
            // deskripsi tidak dikirim
        ];

        $resp = $this->post(route('ukuran.store'), $payload);

        $resp->assertSessionHasErrors(['deskripsi']);

        $this->assertDatabaseMissing('m_ukuran', [
            'nama_ukuran' => 'XXXL',
        ]);
    }

    /** @test */
    public function create_ukuran_gagal_ketika_nama_kosong()
    {
        $payload = [
            'nama_ukuran' => '',
            'deskripsi'   => 'Deskripsi test',
        ];

        $resp = $this->post(route('ukuran.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nama_ukuran']);

        $this->assertDatabaseMissing('m_ukuran', [
            'deskripsi' => 'Deskripsi test',
        ]);
    }

    /** @test */
    public function create_ukuran_gagal_ketika_nama_terlalu_panjang()
    {
        $payload = [
            'nama_ukuran' => str_repeat('a', 256),
            'deskripsi'   => 'Deskripsi test',
        ];

        $resp = $this->post(route('ukuran.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nama_ukuran']);

        $this->assertDatabaseMissing('m_ukuran', [
            'nama_ukuran' => str_repeat('a', 256),
        ]);
    }

    /* ============================================================
     * READ (INDEX + SHOW)
     * ============================================================*/

    /** @test */
    public function index_ukuran_menampilkan_data()
    {
        UkuranModel::factory()->count(3)->create();

        $resp = $this->get(route('ukuran.index'));

        $resp->assertStatus(200);
        $resp->assertViewIs('admin.ukuran.index');
        $resp->assertViewHas('ukuran');
    }

    /** @test */
    public function show_ukuran_menampilkan_detail()
    {
        $ukuran = UkuranModel::factory()->create();

        // Pakai header Ajax biar controller return view, bukan redirect
        $resp = $this->get(route('ukuran.show', $ukuran->ukuran_id), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $resp->assertStatus(200);
        $resp->assertViewIs('admin.ukuran.show');
        $resp->assertViewHas('ukuran', fn ($u) => $u->ukuran_id === $ukuran->ukuran_id);
    }

    /* ============================================================
     * UPDATE
     * ============================================================*/

    /** @test */
    public function update_ukuran_sukses()
    {
        $ukuran = UkuranModel::factory()->create();

        $payload = [
            'nama_ukuran' => 'L Updated',
            'deskripsi'   => 'Deskripsi updated',
        ];

        $resp = $this->putJson(route('ukuran.update', $ukuran->ukuran_id), $payload);

        $resp->assertStatus(200)
             ->assertJson([
                 'success' => true,
                 'message' => 'Ukuran berhasil diperbarui',
             ]);

        $this->assertDatabaseHas('m_ukuran', [
            'nama_ukuran' => 'L Updated',
            'deskripsi'   => 'Deskripsi updated',
            'ukuran_id'   => $ukuran->ukuran_id,
        ]);
    }

    /** @test */
    public function update_ukuran_gagal_ketika_nama_kosong()
    {
        $ukuran = UkuranModel::factory()->create();

        $payload = [
            'nama_ukuran' => '',
            'deskripsi'   => 'Deskripsi test',
        ];

        $resp = $this->put(route('ukuran.update', $ukuran->ukuran_id), $payload);

        $resp->assertSessionHasErrors(['nama_ukuran']);
        $this->assertDatabaseMissing('m_ukuran', [
            'nama_ukuran' => '',
            'ukuran_id'   => $ukuran->ukuran_id,
        ]);
    }

    /** @test */
    public function update_ukuran_gagal_ketika_ukuran_tidak_ada()
    {
        $ukuran_id_tidak_ada = 999999;

        $payload = [
            'nama_ukuran' => 'Ukuran Update Gagal',
            'deskripsi'   => 'Deskripsi update gagal',
        ];

        $resp = $this->put(route('ukuran.update', $ukuran_id_tidak_ada), $payload);

        $resp->assertStatus(404);
    }

    /* ============================================================
     * DELETE
     * ============================================================*/

    /** @test */
    public function delete_ukuran_sukses()
    {
        $ukuran = UkuranModel::factory()->create();

        $resp = $this->deleteJson(route('ukuran.destroy', $ukuran->ukuran_id));

        $resp->assertStatus(200)
             ->assertJson([
                 'success' => true,
                 'message' => 'Ukuran berhasil dihapus',
             ]);

        $this->assertDatabaseMissing('m_ukuran', [
            'ukuran_id' => $ukuran->ukuran_id,
        ]);
    }

    /** @test */
    public function delete_ukuran_gagal_ketika_ukuran_tidak_ada()
    {
        $ukuran_id_tidak_ada = 999999;

        $resp = $this->delete(route('ukuran.destroy', $ukuran_id_tidak_ada));

        $resp->assertStatus(404);
        $this->assertDatabaseMissing('m_ukuran', [
            'ukuran_id' => $ukuran_id_tidak_ada,
        ]);
    }

    /* ============================================================
     * SHOW gagal
     * ============================================================*/

    /** @test */
    public function show_ukuran_gagal_ketika_ukuran_tidak_ada()
    {
        $ukuran_id_tidak_ada = 999999;

        $resp = $this->get(route('ukuran.show', $ukuran_id_tidak_ada), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $resp->assertStatus(404);
    }
}
