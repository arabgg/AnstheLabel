<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\EkspedisiModel;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EkspedisiTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Disable CSRF token for testing
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        Storage::fake('public');
    }

    // --- Store ---

    /** @test */
    public function create_ekspedisi_sukses()
    {
        $payload = [
            'nama_ekspedisi' => 'JNE Express',
            'status_ekspedisi' => '1',
        ];

        $resp = $this->post(route('ekspedisi.store'), $payload);

        $resp->assertRedirect(route('ekspedisi.index'));
        $resp->assertSessionHas('success', 'Ekspedisi berhasil ditambahkan.');

        $this->assertDatabaseHas('m_ekspedisi', [
            'nama_ekspedisi' => 'JNE Express',
            'status_ekspedisi' => '1',
        ]);
    }

    /** @test */
    public function create_ekspedisi_dengan_icon_sukses()
    {
        $file = UploadedFile::fake()->image('jne.png');

        $payload = [
            'nama_ekspedisi' => 'JNE Express',
            'status_ekspedisi' => '1',
            'icon' => $file,
        ];

        $resp = $this->post(route('ekspedisi.store'), $payload);

        $resp->assertRedirect(route('ekspedisi.index'));
        $resp->assertSessionHas('success', 'Ekspedisi berhasil ditambahkan.');

        $this->assertDatabaseHas('m_ekspedisi', [
            'nama_ekspedisi' => 'JNE Express',
            'status_ekspedisi' => '1',
        ]);

        // Check if file exists in storage
        $this->assertTrue(Storage::disk('public')->exists('icons/' . $file->hashName()));
    }

    /** @test */
    public function create_ekspedisi_gagal_ketika_nama_kosong()
    {
        $payload = [
            'nama_ekspedisi' => '',
            'status_ekspedisi' => '1',
        ];

        $resp = $this->post(route('ekspedisi.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nama_ekspedisi']);

        $this->assertDatabaseMissing('m_ekspedisi', [
            'status_ekspedisi' => '1',
        ]);
    }

    /** @test */
    public function create_ekspedisi_gagal_ketika_status_kosong()
    {
        $payload = [
            'nama_ekspedisi' => 'JNE Express',
            'status_ekspedisi' => '',
        ];

        $resp = $this->post(route('ekspedisi.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['status_ekspedisi']);

        $this->assertDatabaseMissing('m_ekspedisi', [
            'nama_ekspedisi' => 'JNE Express',
        ]);
    }

    // --- Index & Show ---

    /** @test */
    public function index_ekspedisi_menampilkan_data()
    {
        EkspedisiModel::factory()->count(3)->create();

        $resp = $this->get(route('ekspedisi.index'));

        $resp->assertStatus(200);
        $resp->assertViewIs('admin.ekspedisi.index');
        $resp->assertViewHas('ekspedisi');
    }

    /** @test */
    public function show_ekspedisi_menampilkan_detail()
    {
        $ekspedisi = EkspedisiModel::factory()->create();

        $resp = $this->get(route('ekspedisi.show', $ekspedisi->ekspedisi_id));

        $resp->assertStatus(200);
        $resp->assertViewIs('admin.ekspedisi.show');
        $resp->assertViewHas('ekspedisi', fn ($e) => $e->ekspedisi_id === $ekspedisi->ekspedisi_id);
    }

    // --- Update ---

    /** @test */
    public function update_ekspedisi_sukses()
    {
        $ekspedisi = EkspedisiModel::factory()->create();

        $payload = [
            'nama_ekspedisi' => 'JNE Express Updated',
            'status_ekspedisi' => '0',
            'old_icon' => $ekspedisi->icon ?? '',
        ];

        $resp = $this->putJson(route('ekspedisi.update', $ekspedisi->ekspedisi_id), $payload);

        $resp->assertStatus(200);
        $resp->assertJson([
            'success' => true,
            'message' => 'Ekspedisi berhasil diperbarui.',
            'data' => [
                'ekspedisi_id' => $ekspedisi->ekspedisi_id,
                'nama_ekspedisi' => 'JNE Express Updated',
                'status_ekspedisi' => '0',
            ],
        ]);

        $this->assertDatabaseHas('m_ekspedisi', [
            'ekspedisi_id' => $ekspedisi->ekspedisi_id,
            'nama_ekspedisi' => 'JNE Express Updated',
            'status_ekspedisi' => '0',
        ]);
    }

    /** @test */
    public function update_ekspedisi_dengan_icon_baru_sukses()
    {
        $ekspedisi = EkspedisiModel::factory()->create(['icon' => 'old-icon.png']);
        $file = UploadedFile::fake()->image('new-jne.png');

        $payload = [
            'nama_ekspedisi' => 'JNE Express Updated',
            'status_ekspedisi' => '1',
            'icon' => $file,
            'old_icon' => 'old-icon.png',
        ];

        $resp = $this->putJson(route('ekspedisi.update', $ekspedisi->ekspedisi_id), $payload);

        $resp->assertStatus(200);
        $resp->assertJson([
            'success' => true,
            'message' => 'Ekspedisi berhasil diperbarui.',
        ]);

        // Check if new file exists
        $this->assertTrue(Storage::disk('public')->exists('icons/' . $file->hashName()));
        // Check if old file is deleted
        $this->assertFalse(Storage::disk('public')->exists('icons/old-icon.png'));
    }

    /** @test */
    public function update_ekspedisi_gagal_ketika_nama_kosong()
    {
        $ekspedisi = EkspedisiModel::factory()->create();

        $payload = [
            'nama_ekspedisi' => '',
            'status_ekspedisi' => '1',
            'old_icon' => $ekspedisi->icon ?? '',
        ];

        $resp = $this->putJson(route('ekspedisi.update', $ekspedisi->ekspedisi_id), $payload);

        $resp->assertStatus(422);
        $resp->assertJsonValidationErrors(['nama_ekspedisi']);

        $this->assertDatabaseMissing('m_ekspedisi', [
            'ekspedisi_id' => $ekspedisi->ekspedisi_id,
            'nama_ekspedisi' => '',
        ]);
    }

    // --- Destroy ---

    /** @test */
    public function delete_ekspedisi_sukses()
    {
        $ekspedisi = EkspedisiModel::factory()->create(['icon' => 'test-icon.png']);

        $resp = $this->deleteJson(route('ekspedisi.destroy', $ekspedisi->ekspedisi_id));

        $resp->assertStatus(200);
        $resp->assertJson([
            'success' => true,
            'message' => 'Ekspedisi berhasil dihapus.',
        ]);

        $this->assertDatabaseMissing('m_ekspedisi', ['ekspedisi_id' => $ekspedisi->ekspedisi_id]);
        // Check if icon file is deleted
        $this->assertFalse(Storage::disk('public')->exists('icons/test-icon.png'));
    }
}
