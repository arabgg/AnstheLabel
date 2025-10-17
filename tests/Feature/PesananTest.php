<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesananTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    private function loginAsAdmin()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    private function createPembayaran()
    {
        return \App\Models\PembayaranModel::factory()->create();
    }

    private function createTransaksi($overrides = [])
    {
        $pembayaran = $this->createPembayaran();
        return \App\Models\TransaksiModel::factory()->create(array_merge([
            'pembayaran_id' => $pembayaran->pembayaran_id,
        ], $overrides));
    }

    public function test_admin_bisa_melihat_daftar_pesanan()
    {
        $this->loginAsAdmin();
        $response = $this->get(route('pesanan.index'));
        $response->assertStatus(200);
        $response->assertViewHas('pesanan');
    }

    public function test_admin_bisa_melihat_detail_pesanan()
    {
        $this->loginAsAdmin();
        $transaksi = $this->createTransaksi();
        $response = $this->get(route('pesanan.show', ['id' => $transaksi->transaksi_id]));
        $response->assertStatus(200);
        $response->assertViewHas('transaksi');
    }

    public function test_admin_bisa_update_status_transaksi_dengan_data_valid()
    {
        $this->loginAsAdmin();
        $transaksi = $this->createTransaksi();
        $data = ['status_transaksi' => 'dikemas'];
        $response = $this->put(route('update.transaksi', ['id' => $transaksi->transaksi_id]), $data);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('t_transaksi', [
            'transaksi_id' => $transaksi->transaksi_id,
            'status_transaksi' => 'dikemas',
        ]);
    }

    public function test_admin_tidak_bisa_update_status_transaksi_dengan_data_tidak_valid()
    {
        $this->loginAsAdmin();
        $transaksi = $this->createTransaksi();
        $data = ['status_transaksi' => 'invalid_status'];
        $response = $this->put(route('update.transaksi', ['id' => $transaksi->transaksi_id]), $data);
        $response->assertSessionHasErrors('status_transaksi');
    }

    // Test untuk export Excel functionality
    public function test_admin_bisa_export_excel_semua_data()
    {
        $this->loginAsAdmin();
        $this->createTransaksi();
        $this->createTransaksi();

        $response = $this->get(route('transaksi.export.excel', [
            'start_date' => null,
            'end_date' => null
        ]));

        $response->assertStatus(200);
        $this->assertEquals('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->headers->get('content-type'));
        $this->assertMatchesRegularExpression('/Transaksi_Semua\.xlsx/', $response->headers->get('content-disposition'));
    }

    public function test_admin_bisa_export_excel_dengan_filter_tanggal()
    {
        $this->loginAsAdmin();
        $this->createTransaksi();

        $startDate = now()->format('Y-m-d');
        $endDate = now()->addDays(1)->format('Y-m-d');

        $response = $this->get(route('transaksi.export.excel', [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]));

        $response->assertStatus(200);
        $this->assertEquals('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->headers->get('content-type'));
        $expectedFileName = 'Transaksi_' . date('d-m-Y', strtotime($startDate)) . '_sampai_' . date('d-m-Y', strtotime($endDate)) . '.xlsx';
        $this->assertMatchesRegularExpression('/' . preg_quote($expectedFileName, '/') . '/', $response->headers->get('content-disposition'));
    }

    public function test_admin_bisa_export_excel_hanya_start_date()
    {
        $this->loginAsAdmin();
        $this->createTransaksi();

        $startDate = now()->format('Y-m-d');

        $response = $this->get(route('transaksi.export.excel', [
            'start_date' => $startDate,
            'end_date' => null
        ]));

        $response->assertStatus(200);
        $expectedFileName = 'Transaksi_mulai_' . date('d-m-Y', strtotime($startDate)) . '.xlsx';
        $this->assertMatchesRegularExpression('/' . preg_quote($expectedFileName, '/') . '/', $response->headers->get('content-disposition'));
    }

    public function test_admin_bisa_export_excel_hanya_end_date()
    {
        $this->loginAsAdmin();
        $this->createTransaksi();

        $endDate = now()->format('Y-m-d');

        $response = $this->get(route('transaksi.export.excel', [
            'start_date' => null,
            'end_date' => $endDate
        ]));

        $response->assertStatus(200);
        $expectedFileName = 'Transaksi_sampai_' . date('d-m-Y', strtotime($endDate)) . '.xlsx';
        $this->assertMatchesRegularExpression('/' . preg_quote($expectedFileName, '/') . '/', $response->headers->get('content-disposition'));
    }

    // Test untuk validasi status transaksi yang lebih komprehensif
    public function test_admin_bisa_update_status_transaksi_menunggu_pembayaran()
    {
        $this->loginAsAdmin();
        $transaksi = $this->createTransaksi();
        $data = ['status_transaksi' => 'menunggu pembayaran'];
        $response = $this->put(route('update.transaksi', ['id' => $transaksi->transaksi_id]), $data);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('t_transaksi', [
            'transaksi_id' => $transaksi->transaksi_id,
            'status_transaksi' => 'menunggu pembayaran',
        ]);
    }

    public function test_admin_bisa_update_status_transaksi_dikemas()
    {
        $this->loginAsAdmin();
        $transaksi = $this->createTransaksi();
        $data = ['status_transaksi' => 'dikemas'];
        $response = $this->put(route('update.transaksi', ['id' => $transaksi->transaksi_id]), $data);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('t_transaksi', [
            'transaksi_id' => $transaksi->transaksi_id,
            'status_transaksi' => 'dikemas',
        ]);
    }

    public function test_admin_bisa_update_status_transaksi_dikirim()
    {
        $this->loginAsAdmin();
        $transaksi = $this->createTransaksi();
        $data = ['status_transaksi' => 'dikirim'];
        $response = $this->put(route('update.transaksi', ['id' => $transaksi->transaksi_id]), $data);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('t_transaksi', [
            'transaksi_id' => $transaksi->transaksi_id,
            'status_transaksi' => 'dikirim',
        ]);
    }

    public function test_admin_bisa_update_status_transaksi_selesai()
    {
        $this->loginAsAdmin();
        $transaksi = $this->createTransaksi();
        $data = ['status_transaksi' => 'selesai'];
        $response = $this->put(route('update.transaksi', ['id' => $transaksi->transaksi_id]), $data);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('t_transaksi', [
            'transaksi_id' => $transaksi->transaksi_id,
            'status_transaksi' => 'selesai',
        ]);
    }

    public function test_admin_bisa_update_status_transaksi_batal()
    {
        $this->loginAsAdmin();
        $transaksi = $this->createTransaksi();
        $data = ['status_transaksi' => 'batal'];
        $response = $this->put(route('update.transaksi', ['id' => $transaksi->transaksi_id]), $data);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('t_transaksi', [
            'transaksi_id' => $transaksi->transaksi_id,
            'status_transaksi' => 'batal',
        ]);
    }

    public function test_admin_tidak_bisa_update_status_transaksi_tanpa_login()
    {
        $transaksi = $this->createTransaksi();
        $data = ['status_transaksi' => 'dikemas'];
        $response = $this->put(route('update.transaksi', ['id' => $transaksi->transaksi_id]), $data);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_tidak_bisa_melihat_pesanan_tanpa_login()
    {
        $response = $this->get(route('pesanan.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_tidak_bisa_melihat_detail_pesanan_tanpa_login()
    {
        $transaksi = $this->createTransaksi();
        $response = $this->get(route('pesanan.show', ['id' => $transaksi->transaksi_id]));
        $response->assertRedirect(route('login'));
    }
}
