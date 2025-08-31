<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\BahanModel;
use App\Http\Middleware\Authenticate;

class BahanTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable middleware auth supaya POST bisa langsung di-test
        $this->withoutMiddleware(Authenticate::class);
    }

    /** @test */
    public function create_bahan_sukses()
    {
        $payload = [
            'nama_bahan' => 'Katun',
            'deskripsi' => 'Bahan katun berkualitas',
        ];

        $resp = $this->post(route('bahan.store'), $payload);

        $resp->assertRedirect(route('bahan.index'));
        $resp->assertSessionHas('success', 'Bahan berhasil ditambahkan!');

        $this->assertDatabaseHas('m_bahan', [
            'nama_bahan' => 'Katun',
            'deskripsi' => 'Bahan katun berkualitas',
        ]);
    }

    /** @test */
    public function create_bahan_gagal_ketika_nama_kosong()
    {
        $payload = [
            'nama_bahan' => '',
            'deskripsi' => 'Deskripsi test',
        ];

        $resp = $this->post(route('bahan.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nama_bahan']);

        $this->assertDatabaseMissing('m_bahan', [
            'deskripsi' => 'Deskripsi test',
        ]);
    }

    /** @test */
    public function index_bahan_menampilkan_data()
    {
        BahanModel::factory()->count(3)->create();

        $resp = $this->get(route('bahan.index'));

        $resp->assertStatus(200);
        $resp->assertViewIs('bahan.index');
        $resp->assertViewHas('bahan');
    }

    /** @test */
    public function show_bahan_menampilkan_detail()
    {
        $bahan = BahanModel::factory()->create();

        $resp = $this->get(route('bahan.show', $bahan->bahan_id));

        $resp->assertStatus(200);
        $resp->assertViewIs('bahan.show');
        $resp->assertViewHas('bahan', fn ($b) => $b->bahan_id === $bahan->bahan_id);
    }

    /** @test */
    public function update_bahan_sukses()
    {
        $bahan = BahanModel::factory()->create();

        $payload = [
            'nama_bahan' => 'Katun Updated',
            'deskripsi' => 'Deskripsi updated',
        ];

        $resp = $this->put(route('bahan.update', $bahan->bahan_id), $payload);

        $resp->assertRedirect(route('bahan.index'));
        $resp->assertSessionHas('success', 'Bahan berhasil diperbarui!');
        $this->assertDatabaseHas('m_bahan', [
            'nama_bahan' => 'Katun Updated',
            'deskripsi' => 'Deskripsi updated',
            'bahan_id' => $bahan->bahan_id
        ]);
    }

    /** @test */
    public function update_bahan_gagal_ketika_nama_kosong()
    {
        $bahan = BahanModel::factory()->create();

        $payload = [
            'nama_bahan' => '',
            'deskripsi' => 'Deskripsi test',
        ];

        $resp = $this->put(route('bahan.update', $bahan->bahan_id), $payload);

        $resp->assertSessionHasErrors(['nama_bahan']);
        $this->assertDatabaseMissing('m_bahan', [
            'nama_bahan' => '',
            'bahan_id' => $bahan->bahan_id
        ]);
    }

    /** @test */
    public function delete_bahan_sukses()
    {
        $bahan = BahanModel::factory()->create();

        $resp = $this->delete(route('bahan.destroy', $bahan->bahan_id));

        $resp->assertRedirect(route('bahan.index'));
        $resp->assertSessionHas('success', 'Bahan berhasil dihapus!');
        $this->assertDatabaseMissing('m_bahan', ['bahan_id' => $bahan->bahan_id]);
    }
}
