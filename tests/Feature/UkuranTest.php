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

        // Disable middleware auth supaya POST bisa langsung di-test
        $this->withoutMiddleware(Authenticate::class);
    }

    /* ============================================================
     * CREATE
     * ============================================================*/

    /** @test */
    public function create_ukuran_sukses()
    {
        // Payload yang dikirim ke controller ukuran.store
        $payload = [
            'nama_ukuran' => 'XL',
            'deskripsi' => 'Ukuran Extra Large',
        ];

        // Act → request create ukuran
        $resp = $this->post(route('ukuran.store'), $payload);

        // Assert → redirect sukses dan ada flash message
        $resp->assertRedirect(route('ukuran.index'));
        $resp->assertSessionHas('success', 'Ukuran berhasil ditambahkan!');

        // Assert → cek ukuran tersimpan di DB
        $this->assertDatabaseHas('m_ukuran', [
            'nama_ukuran' => 'XL',
            'deskripsi' => 'Ukuran Extra Large',
        ]);
    }

    /** @test */
    public function create_ukuran_sukses_tanpa_deskripsi()
    {
        // Payload yang dikirim ke controller ukuran.store
        $payload = [
            'nama_ukuran' => 'M',
            'deskripsi' => null, // deskripsi boleh kosong
        ];

        // Act → request create ukuran
        $resp = $this->post(route('ukuran.store'), $payload);

        // Assert → redirect sukses dan ada flash message
        $resp->assertRedirect(route('ukuran.index'));
        $resp->assertSessionHas('success', 'Ukuran berhasil ditambahkan!');

        // Assert → cek ukuran tersimpan di DB
        $this->assertDatabaseHas('m_ukuran', [
            'nama_ukuran' => 'M',
            'deskripsi' => null,
        ]);
    }

    /** @test */
    public function create_ukuran_gagal_ketika_nama_kosong()
    {
        // Payload yang dikirim ke controller ukuran.store
        $payload = [
            'nama_ukuran' => '', // kosong
            'deskripsi' => 'Deskripsi test',
        ];

        // Act → request create ukuran
        $resp = $this->post(route('ukuran.store'), $payload);

        // Assert → redirect kembali ke form dengan error validasi
        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nama_ukuran']);

        // Assert → cek ukuran TIDAK tersimpan di DB
        $this->assertDatabaseMissing('m_ukuran', [
            'deskripsi' => 'Deskripsi test',
        ]);
    }

    /** @test */
    public function create_ukuran_gagal_ketika_nama_terlalu_panjang()
    {
        // Payload yang dikirim ke controller ukuran.store
        $payload = [
            'nama_ukuran' => str_repeat('a', 256), // lebih dari 255 karakter
            'deskripsi' => 'Deskripsi test',
        ];

        // Act → request create ukuran
        $resp = $this->post(route('ukuran.store'), $payload);

        // Assert → redirect kembali ke form dengan error validasi
        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nama_ukuran']);

        // Assert → cek ukuran TIDAK tersimpan di DB
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
        $resp->assertViewIs('ukuran.index');
        $resp->assertViewHas('ukuran');
    }

    /** @test */
    public function show_ukuran_menampilkan_detail()
    {
        $ukuran = UkuranModel::factory()->create();

        $resp = $this->get(route('ukuran.show', $ukuran->ukuran_id));

        $resp->assertStatus(200);
        $resp->assertViewIs('ukuran.show');
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
            'deskripsi' => 'Deskripsi updated',
        ];

        $resp = $this->put(route('ukuran.update', $ukuran->ukuran_id), $payload);

        $resp->assertRedirect(route('ukuran.index'));
        $resp->assertSessionHas('success', 'Ukuran berhasil diupdate!');
        $this->assertDatabaseHas('m_ukuran', [
            'nama_ukuran' => 'L Updated',
            'deskripsi' => 'Deskripsi updated',
            'ukuran_id' => $ukuran->ukuran_id
        ]);
    }

    /** @test */
    public function update_ukuran_gagal_ketika_nama_kosong()
    {
        $ukuran = UkuranModel::factory()->create();

        $payload = [
            'nama_ukuran' => '',
            'deskripsi' => 'Deskripsi test',
        ];

        $resp = $this->put(route('ukuran.update', $ukuran->ukuran_id), $payload);

        $resp->assertSessionHasErrors(['nama_ukuran']);
        $this->assertDatabaseMissing('m_ukuran', [
            'nama_ukuran' => '',
            'ukuran_id' => $ukuran->ukuran_id
        ]);
    }

    /* ============================================================
     * DELETE
     * ============================================================*/

    /** @test */
    public function delete_ukuran_sukses()
    {
        $ukuran = UkuranModel::factory()->create();

        $resp = $this->delete(route('ukuran.destroy', $ukuran->ukuran_id));

        $resp->assertRedirect(route('ukuran.index'));
        $resp->assertSessionHas('success', 'Ukuran berhasil dihapus!');
        $this->assertDatabaseMissing('m_ukuran', ['ukuran_id' => $ukuran->ukuran_id]);
    }
}
