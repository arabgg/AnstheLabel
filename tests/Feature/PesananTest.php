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

    public function test_admin_bisa_update_status_pembayaran_dengan_data_valid()
    {
        $this->loginAsAdmin();
        $pembayaran = $this->createPembayaran();
        $data = ['status_pembayaran' => 'lunas'];
        $response = $this->put(route('update.pembayaran', ['id' => $pembayaran->pembayaran_id]), $data);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('t_pembayaran', [
            'pembayaran_id' => $pembayaran->pembayaran_id,
            'status_pembayaran' => 'lunas',
        ]);
    }

    public function test_admin_tidak_bisa_update_status_pembayaran_dengan_data_tidak_valid()
    {
        $this->loginAsAdmin();
        $pembayaran = $this->createPembayaran();
        $data = ['status_pembayaran' => 'invalid_status'];
        $response = $this->put(route('update.pembayaran', ['id' => $pembayaran->pembayaran_id]), $data);
        $response->assertSessionHasErrors('status_pembayaran');
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
}
