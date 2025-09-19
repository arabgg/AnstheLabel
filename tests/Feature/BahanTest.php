<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BahanModel;
use App\Http\Middleware\Authenticate;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BahanTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(Authenticate::class);
    }

    // --- Store ---

    /** @test */
    public function create_bahan_sukses()
    {
        $payload = [
            'nama_bahan' => 'Katun',
            'deskripsi' => 'Bahan katun berkualitas',
        ];

        $resp = $this->post(route('bahan.store'), $payload);

        $resp->assertRedirect(route('bahan.index'));
        $resp->assertSessionHas('success', 'Bahan berhasil ditambahkan');

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

    // --- Index & Show ---

    /** @test */
    public function index_bahan_menampilkan_data()
    {
        BahanModel::factory()->count(3)->create();

        $resp = $this->get(route('bahan.index'));

        $resp->assertStatus(200);
        $resp->assertViewIs('admin.bahan.index');
        $resp->assertViewHas('bahan');
    }

    /** @test */
    public function show_bahan_menampilkan_detail()
    {
        $bahan = BahanModel::factory()->create();

        $resp = $this->get(route('bahan.show', $bahan->bahan_id), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $resp->assertStatus(200);
        $resp->assertViewIs('admin.bahan.show');
        $resp->assertViewHas('bahan', fn ($b) => $b->bahan_id === $bahan->bahan_id);
    }

    // --- Update ---

    /** @test */
    public function update_bahan_sukses()
    {
        $bahan = BahanModel::factory()->create();

        $payload = [
            'nama_bahan' => 'Katun Updated',
            'deskripsi' => 'Deskripsi updated',
        ];

        $resp = $this->putJson(route('bahan.update', $bahan->bahan_id), $payload);

        $resp->assertStatus(200);
        $resp->assertJson([
            'success' => true,
            'message' => 'Bahan berhasil diperbarui',
            'data' => [
                'bahan_id' => $bahan->bahan_id,
                'nama_bahan' => 'Katun Updated',
                'deskripsi' => 'Deskripsi updated',
            ],
        ]);

        $this->assertDatabaseHas('m_bahan', [
            'bahan_id' => $bahan->bahan_id,
            'nama_bahan' => 'Katun Updated',
            'deskripsi' => 'Deskripsi updated',
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

        $resp = $this->putJson(route('bahan.update', $bahan->bahan_id), $payload);

        $resp->assertStatus(422);
        $resp->assertJsonValidationErrors(['nama_bahan']);

        $this->assertDatabaseMissing('m_bahan', [
            'bahan_id' => $bahan->bahan_id,
            'nama_bahan' => '',
        ]);
    }

    // --- Destroy ---

    /** @test */
    public function delete_bahan_sukses()
    {
        $bahan = BahanModel::factory()->create();

        $resp = $this->deleteJson(route('bahan.destroy', $bahan->bahan_id));

        $resp->assertStatus(200);
        $resp->assertJson([
            'success' => true,
            'message' => 'Bahan berhasil dihapus',
            'id' => $bahan->bahan_id,
        ]);

        $this->assertDatabaseMissing('m_bahan', ['bahan_id' => $bahan->bahan_id]);
    }
}
