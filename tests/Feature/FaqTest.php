<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\FaqModel;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FaqTest extends TestCase
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
    }

    // --- Store ---

    /** @test */
    public function create_faq_sukses()
    {
        $payload = [
            'pertanyaan' => 'Apa itu Laravel?',
            'jawaban' => 'Laravel adalah framework PHP yang powerful untuk web development.',
        ];

        $resp = $this->post(route('faq.store'), $payload);

        $resp->assertRedirect(route('faq.index'));
        $resp->assertSessionHas('success', 'FAQ berhasil ditambahkan.');

        $this->assertDatabaseHas('m_faq', [
            'pertanyaan' => 'Apa itu Laravel?',
            'jawaban' => 'Laravel adalah framework PHP yang powerful untuk web development.',
        ]);
    }

    /** @test */
    public function create_faq_gagal_ketika_pertanyaan_kosong()
    {
        $payload = [
            'pertanyaan' => '',
            'jawaban' => 'Jawaban test',
        ];

        $resp = $this->post(route('faq.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['pertanyaan']);

        $this->assertDatabaseMissing('m_faq', [
            'jawaban' => 'Jawaban test',
        ]);
    }

    /** @test */
    public function create_faq_gagal_ketika_jawaban_kosong()
    {
        $payload = [
            'pertanyaan' => 'Pertanyaan test',
            'jawaban' => '',
        ];

        $resp = $this->post(route('faq.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['jawaban']);

        $this->assertDatabaseMissing('m_faq', [
            'pertanyaan' => 'Pertanyaan test',
        ]);
    }

    // --- Index & Show ---

    /** @test */
    public function index_faq_menampilkan_data()
    {
        FaqModel::factory()->count(3)->create();

        $resp = $this->get(route('faq.index'));

        $resp->assertStatus(200);
        $resp->assertViewIs('admin.faq.index');
        $resp->assertViewHas('faqs');
    }

    /** @test */
    public function show_faq_menampilkan_detail()
    {
        $faq = FaqModel::factory()->create();

        $resp = $this->get(route('faq.show', $faq->faq_id), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $resp->assertStatus(200);
        $resp->assertViewIs('admin.faq.show');
        $resp->assertViewHas('faq', fn ($f) => $f->faq_id === $faq->faq_id);
    }

    // --- Update ---

    /** @test */
    public function update_faq_sukses()
    {
        $faq = FaqModel::factory()->create();

        $payload = [
            'pertanyaan' => 'Apa itu Laravel? (Updated)',
            'jawaban' => 'Laravel adalah framework PHP yang powerful untuk web development. (Updated)',
        ];

        $resp = $this->putJson(route('faq.update', $faq->faq_id), $payload);

        $resp->assertStatus(200);
        $resp->assertJson([
            'success' => true,
            'message' => 'FAQ berhasil diperbarui.',
            'data' => [
                'faq_id' => $faq->faq_id,
                'pertanyaan' => 'Apa itu Laravel? (Updated)',
                'jawaban' => 'Laravel adalah framework PHP yang powerful untuk web development. (Updated)',
            ],
        ]);

        $this->assertDatabaseHas('m_faq', [
            'faq_id' => $faq->faq_id,
            'pertanyaan' => 'Apa itu Laravel? (Updated)',
            'jawaban' => 'Laravel adalah framework PHP yang powerful untuk web development. (Updated)',
        ]);
    }

    /** @test */
    public function update_faq_gagal_ketika_pertanyaan_kosong()
    {
        $faq = FaqModel::factory()->create();

        $payload = [
            'pertanyaan' => '',
            'jawaban' => 'Jawaban test',
        ];

        $resp = $this->putJson(route('faq.update', $faq->faq_id), $payload);

        $resp->assertStatus(422);
        $resp->assertJsonValidationErrors(['pertanyaan']);

        $this->assertDatabaseMissing('m_faq', [
            'faq_id' => $faq->faq_id,
            'pertanyaan' => '',
        ]);
    }

    /** @test */
    public function update_faq_gagal_ketika_jawaban_kosong()
    {
        $faq = FaqModel::factory()->create();

        $payload = [
            'pertanyaan' => 'Pertanyaan test',
            'jawaban' => '',
        ];

        $resp = $this->putJson(route('faq.update', $faq->faq_id), $payload);

        $resp->assertStatus(422);
        $resp->assertJsonValidationErrors(['jawaban']);

        $this->assertDatabaseMissing('m_faq', [
            'faq_id' => $faq->faq_id,
            'jawaban' => '',
        ]);
    }

    // --- Destroy ---

    /** @test */
    public function delete_faq_sukses()
    {
        $faq = FaqModel::factory()->create();

        $resp = $this->deleteJson(route('faq.destroy', $faq->faq_id));

        $resp->assertStatus(200);
        $resp->assertJson([
            'success' => true,
            'message' => 'FAQ berhasil dihapus.',
            'id' => $faq->faq_id,
        ]);

        $this->assertDatabaseMissing('m_faq', ['faq_id' => $faq->faq_id]);
    }
}
