<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\VoucherModel;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VoucherTest extends TestCase
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
    public function create_voucher_sukses()
    {
        $payload = [
            'kode_voucher' => 'DISKON10',
            'deskripsi' => 'Diskon 10% untuk pembelian pertama',
            'tipe_diskon' => 'persen',
            'nilai_diskon' => 10,
            'min_transaksi' => 50000,
            'usage_limit' => 100,
            'status_voucher' => true,
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_berakhir' => now()->addDays(30)->format('Y-m-d'),
        ];

        $resp = $this->post(route('voucher.store'), $payload);

        $resp->assertRedirect(route('voucher.index'));
        $resp->assertSessionHas('success', 'Voucher berhasil ditambahkan');

        $this->assertDatabaseHas('m_voucher', [
            'kode_voucher' => 'DISKON10',
            'tipe_diskon' => 'persen',
            'nilai_diskon' => 10,
            'status_voucher' => true,
        ]);
    }

    /** @test */
    public function create_voucher_nominal_sukses()
    {
        $payload = [
            'kode_voucher' => 'DISKON50K',
            'deskripsi' => 'Diskon Rp 50.000',
            'tipe_diskon' => 'nominal',
            'nilai_diskon' => 50000,
            'min_transaksi' => 100000,
            'usage_limit' => 50,
            'status_voucher' => true,
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_berakhir' => now()->addDays(60)->format('Y-m-d'),
        ];

        $resp = $this->post(route('voucher.store'), $payload);

        $resp->assertRedirect(route('voucher.index'));
        $resp->assertSessionHas('success', 'Voucher berhasil ditambahkan');

        $this->assertDatabaseHas('m_voucher', [
            'kode_voucher' => 'DISKON50K',
            'tipe_diskon' => 'nominal',
            'nilai_diskon' => 50000,
            'status_voucher' => true,
        ]);
    }

    /** @test */
    public function create_voucher_gagal_ketika_kode_sudah_ada()
    {
        VoucherModel::factory()->create(['kode_voucher' => 'DISKON10']);

        $payload = [
            'kode_voucher' => 'DISKON10',
            'deskripsi' => 'Diskon 10% untuk pembelian pertama',
            'tipe_diskon' => 'persen',
            'nilai_diskon' => 10,
            'min_transaksi' => 50000,
            'usage_limit' => 100,
            'status_voucher' => true,
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_berakhir' => now()->addDays(30)->format('Y-m-d'),
        ];

        $resp = $this->post(route('voucher.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['kode_voucher']);

        $this->assertDatabaseMissing('m_voucher', [
            'kode_voucher' => 'DISKON10',
            'deskripsi' => 'Diskon 10% untuk pembelian pertama',
        ]);
    }

    /** @test */
    public function create_voucher_gagal_ketika_kode_kosong()
    {
        $payload = [
            'kode_voucher' => '',
            'deskripsi' => 'Diskon 10% untuk pembelian pertama',
            'tipe_diskon' => 'persen',
            'nilai_diskon' => 10,
            'min_transaksi' => 50000,
            'usage_limit' => 100,
            'status_voucher' => true,
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_berakhir' => now()->addDays(30)->format('Y-m-d'),
        ];

        $resp = $this->post(route('voucher.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['kode_voucher']);

        $this->assertDatabaseMissing('m_voucher', [
            'deskripsi' => 'Diskon 10% untuk pembelian pertama',
        ]);
    }

    /** @test */
    public function create_voucher_gagal_ketika_tipe_diskon_kosong()
    {
        $payload = [
            'kode_voucher' => 'DISKON10',
            'deskripsi' => 'Diskon 10% untuk pembelian pertama',
            'tipe_diskon' => '',
            'nilai_diskon' => 10,
            'min_transaksi' => 50000,
            'usage_limit' => 100,
            'status_voucher' => true,
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_berakhir' => now()->addDays(30)->format('Y-m-d'),
        ];

        $resp = $this->post(route('voucher.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['tipe_diskon']);

        $this->assertDatabaseMissing('m_voucher', [
            'kode_voucher' => 'DISKON10',
        ]);
    }

    /** @test */
    public function create_voucher_gagal_ketika_nilai_diskon_kosong()
    {
        $payload = [
            'kode_voucher' => 'DISKON10',
            'deskripsi' => 'Diskon 10% untuk pembelian pertama',
            'tipe_diskon' => 'persen',
            'nilai_diskon' => '',
            'min_transaksi' => 50000,
            'usage_limit' => 100,
            'status_voucher' => true,
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_berakhir' => now()->addDays(30)->format('Y-m-d'),
        ];

        $resp = $this->post(route('voucher.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['nilai_diskon']);

        $this->assertDatabaseMissing('m_voucher', [
            'kode_voucher' => 'DISKON10',
        ]);
    }

    /** @test */
    public function create_voucher_gagal_ketika_status_kosong()
    {
        $payload = [
            'kode_voucher' => 'DISKON10',
            'deskripsi' => 'Diskon 10% untuk pembelian pertama',
            'tipe_diskon' => 'persen',
            'nilai_diskon' => 10,
            'min_transaksi' => 50000,
            'usage_limit' => 100,
            'status_voucher' => '',
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_berakhir' => now()->addDays(30)->format('Y-m-d'),
        ];

        $resp = $this->post(route('voucher.store'), $payload);

        $resp->assertStatus(302);
        $resp->assertSessionHasErrors(['status_voucher']);

        $this->assertDatabaseMissing('m_voucher', [
            'kode_voucher' => 'DISKON10',
        ]);
    }

    // --- Index & Show ---

    /** @test */
    public function index_voucher_menampilkan_data()
    {
        VoucherModel::factory()->count(3)->create();

        $resp = $this->get(route('voucher.index'));

        $resp->assertStatus(200);
        $resp->assertViewIs('admin.voucher.index');
        $resp->assertViewHas('voucher');
    }

    /** @test */
    public function show_voucher_menampilkan_detail()
    {
        $voucher = VoucherModel::factory()->create();

        $resp = $this->get(route('voucher.show', $voucher->voucher_id), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $resp->assertStatus(200);
        $resp->assertViewIs('admin.voucher.show');
        $resp->assertViewHas('voucher', fn ($v) => $v->voucher_id === $voucher->voucher_id);
    }

    // --- Update ---

    /** @test */
    public function update_voucher_sukses()
    {
        $voucher = VoucherModel::factory()->create();

        $payload = [
            'kode_voucher' => 'DISKON20',
            'deskripsi' => 'Diskon 20% untuk member',
            'tipe_diskon' => 'persen',
            'nilai_diskon' => 20,
            'min_transaksi' => 75000,
            'usage_limit' => 200,
            'status_voucher' => false,
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_berakhir' => now()->addDays(45)->format('Y-m-d'),
        ];

        $resp = $this->putJson(route('voucher.update', $voucher->voucher_id), $payload);

        $resp->assertStatus(200);
        $resp->assertJson([
            'success' => true,
            'message' => 'Voucher berhasil diperbarui',
            'voucher' => [
                'voucher_id' => $voucher->voucher_id,
                'kode_voucher' => 'DISKON20',
                'deskripsi' => 'Diskon 20% untuk member',
                'tipe_diskon' => 'persen',
                'nilai_diskon' => 20,
                'status_voucher' => false,
            ],
        ]);

        $this->assertDatabaseHas('m_voucher', [
            'voucher_id' => $voucher->voucher_id,
            'kode_voucher' => 'DISKON20',
            'deskripsi' => 'Diskon 20% untuk member',
            'tipe_diskon' => 'persen',
            'nilai_diskon' => 20,
            'status_voucher' => false,
        ]);
    }

    /** @test */
    public function update_voucher_gagal_ketika_kode_sudah_ada()
    {
        $voucher1 = VoucherModel::factory()->create(['kode_voucher' => 'DISKON10']);
        $voucher2 = VoucherModel::factory()->create(['kode_voucher' => 'DISKON20']);

        $payload = [
            'kode_voucher' => 'DISKON10',
            'deskripsi' => 'Diskon 10% updated',
            'tipe_diskon' => 'persen',
            'nilai_diskon' => 10,
            'min_transaksi' => 50000,
            'usage_limit' => 100,
            'status_voucher' => true,
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_berakhir' => now()->addDays(30)->format('Y-m-d'),
        ];

        $resp = $this->putJson(route('voucher.update', $voucher2->voucher_id), $payload);

        $resp->assertStatus(422);
        $resp->assertJsonValidationErrors(['kode_voucher']);

        $this->assertDatabaseMissing('m_voucher', [
            'voucher_id' => $voucher2->voucher_id,
            'kode_voucher' => 'DISKON10',
        ]);
    }

    /** @test */
    public function update_voucher_gagal_ketika_kode_kosong()
    {
        $voucher = VoucherModel::factory()->create();

        $payload = [
            'kode_voucher' => '',
            'deskripsi' => 'Diskon 10% updated',
            'tipe_diskon' => 'persen',
            'nilai_diskon' => 10,
            'min_transaksi' => 50000,
            'usage_limit' => 100,
            'status_voucher' => true,
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_berakhir' => now()->addDays(30)->format('Y-m-d'),
        ];

        $resp = $this->putJson(route('voucher.update', $voucher->voucher_id), $payload);

        $resp->assertStatus(422);
        $resp->assertJsonValidationErrors(['kode_voucher']);

        $this->assertDatabaseMissing('m_voucher', [
            'voucher_id' => $voucher->voucher_id,
            'kode_voucher' => '',
        ]);
    }

    // --- Destroy ---

    /** @test */
    public function delete_voucher_sukses()
    {
        $voucher = VoucherModel::factory()->create();

        $resp = $this->deleteJson(route('voucher.destroy', $voucher->voucher_id));

        $resp->assertStatus(200);
        $resp->assertJson([
            'success' => true,
            'message' => 'Voucher berhasil dihapus',
            'id' => $voucher->voucher_id,
        ]);

        $this->assertDatabaseMissing('m_voucher', ['voucher_id' => $voucher->voucher_id]);
    }
}
