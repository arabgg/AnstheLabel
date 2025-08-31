<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransaksiTest extends TestCase
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

    private function validData($overrides = [])
    {
        $pembayaran = $this->createPembayaran();
        return array_merge([
            'pembayaran_id' => $pembayaran->pembayaran_id,
            'kode_invoice' => 'INV' . rand(1000,9999),
            'nama_customer' => 'Test Customer',
            'no_telp' => '08123456789',
            'email' => 'test@example.com',
            'alamat' => 'Jl. Test',
            'status_transaksi' => 'menunggu pembayaran', // enum valid
        ], $overrides);
    }

    // INDEX (jika ada route list transaksi)
    public function test_admin_bisa_melihat_daftar_transaksi()
    {
        $this->loginAsAdmin();
        $response = $this->get('/admin/transaksi', ['Accept' => 'text/html']);
        $response->assertStatus(200);
    }

    // CREATE (Positive)
    public function test_admin_bisa_menambah_transaksi()
    {
        $this->loginAsAdmin();
        $data = $this->validData();
        $response = $this->post('/admin/transaksi', $data, ['Accept' => 'text/html']);
        $response->assertRedirect('/admin/transaksi');
        $this->assertDatabaseHas('t_transaksi', [
            'kode_invoice' => $data['kode_invoice'],
            'nama_customer' => $data['nama_customer'],
        ]);
    }

    // CREATE (Negative)
    public function test_admin_tidak_bisa_menambah_transaksi_dengan_data_tidak_valid()
    {
        $this->loginAsAdmin();
        $data = $this->validData(['nama_customer' => '']);
        $response = $this->post('/admin/transaksi', $data, ['Accept' => 'text/html']);
        $response->assertSessionHasErrors('nama_customer');
    }

    // SHOW (Positive)
    public function test_admin_bisa_melihat_detail_transaksi()
    {
        $this->loginAsAdmin();
        $transaksi = \App\Models\TransaksiModel::factory()->create();
        $response = $this->get('/transaksi/' . $transaksi->kode_invoice, ['Accept' => 'text/html']);
        $response->assertStatus(200);
    }

    // SHOW (Negative)
    public function test_admin_tidak_bisa_melihat_transaksi_yang_tidak_ada()
    {
        $this->loginAsAdmin();
        $response = $this->get('/transaksi/INV999999', ['Accept' => 'text/html']);
        $response->assertStatus(404);
    }

    // UPDATE (Positive)
    public function test_admin_bisa_update_transaksi()
    {
        $this->loginAsAdmin();
        $transaksi = \App\Models\TransaksiModel::factory()->create();
        $update = [
            'nama_customer' => 'Update Customer',
            'no_telp' => '0811111111',
            'email' => 'update@example.com',
            'alamat' => 'Jl. Update',
            'status_transaksi' => 'lunas',
        ];
        $response = $this->put('/admin/transaksi/' . $transaksi->transaksi_id, $update, ['Accept' => 'text/html']);
        $response->assertRedirect('/admin/transaksi');
        $this->assertDatabaseHas('t_transaksi', [
            'transaksi_id' => $transaksi->transaksi_id,
            'nama_customer' => 'Update Customer',
        ]);
    }

    // UPDATE (Negative)
    public function test_admin_tidak_bisa_update_transaksi_dengan_data_tidak_valid()
    {
        $this->loginAsAdmin();
        $transaksi = \App\Models\TransaksiModel::factory()->create();
        $update = [
            'nama_customer' => '',
        ];
        $response = $this->put('/admin/transaksi/' . $transaksi->transaksi_id, $update, ['Accept' => 'text/html']);
        $response->assertSessionHasErrors('nama_customer');
    }

    // DELETE (Positive)
    public function test_admin_bisa_menghapus_transaksi()
    {
        $this->loginAsAdmin();
        $transaksi = \App\Models\TransaksiModel::factory()->create();
        $response = $this->delete('/admin/transaksi/' . $transaksi->transaksi_id, [], ['Accept' => 'text/html']);
        $response->assertRedirect('/admin/transaksi');
        $this->assertDatabaseMissing('t_transaksi', [
            'transaksi_id' => $transaksi->transaksi_id,
        ]);
    }

    // DELETE (Negative)
    public function test_admin_tidak_bisa_menghapus_transaksi_yang_tidak_ada()
    {
        $this->loginAsAdmin();
        $response = $this->delete('/admin/transaksi/UUID-TIDAK-ADA', [], ['Accept' => 'text/html']);
        $response->assertStatus(404);
    }
}
