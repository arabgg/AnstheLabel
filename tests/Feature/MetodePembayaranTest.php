<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MetodePembayaranTest extends TestCase
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

    private function createMetode()
    {
        return \App\Models\MetodeModel::factory()->create();
    }

    private function validData($overrides = [])
    {
        $metode = $this->createMetode();
        return array_merge([
            'metode_id' => $metode->metode_id,
            'nama_pembayaran' => 'Test Payment',
            'kode_bayar' => 'KODE-1234',
            'status_pembayaran' => true,
            'icon' => null,
        ], $overrides);
    }

    // INDEX
    public function test_admin_bisa_melihat_daftar_metode_pembayaran()
    {
        $this->loginAsAdmin();
    $response = $this->get('/metode-pembayaran', ['Accept' => 'text/html']);
    $response->assertStatus(200);
    }

    // CREATE (Positive)
    public function test_admin_bisa_menambah_metode_pembayaran()
    {
        $this->loginAsAdmin();
        $data = $this->validData();
        $response = $this->post('/metode-pembayaran', $data, ['Accept' => 'text/html']);
        if ($response->status() === 500) {
            dump($response->getContent());
        }
        $response->assertRedirect('/metode-pembayaran');
        $this->assertDatabaseHas('t_metode_pembayaran', [
            'nama_pembayaran' => $data['nama_pembayaran'],
            'kode_bayar' => $data['kode_bayar'],
        ]);
    }

    // CREATE (Negative)
    public function test_admin_tidak_bisa_menambah_metode_pembayaran_dengan_data_tidak_valid()
    {
        $this->loginAsAdmin();
        $data = $this->validData(['nama_pembayaran' => '']);
    $response = $this->post('/metode-pembayaran', $data, ['Accept' => 'text/html']);
    $response->assertSessionHasErrors('nama_pembayaran');
    }

    // SHOW (Positive)
    public function test_admin_bisa_melihat_detail_metode_pembayaran()
    {
        $this->loginAsAdmin();
        $metode = $this->createMetode();
        $data = \App\Models\MetodePembayaranModel::factory()->create([
            'metode_id' => $metode->metode_id
        ]);
    $response = $this->get('/metode-pembayaran/' . $data->metode_pembayaran_id, ['Accept' => 'text/html']);
    $response->assertStatus(200);
    }

    // SHOW (Negative)
    public function test_admin_tidak_bisa_melihat_metode_pembayaran_yang_tidak_ada()
    {
        $this->loginAsAdmin();
    $response = $this->get('/metode-pembayaran/999999', ['Accept' => 'text/html']);
    $response->assertStatus(404);
    }

    // UPDATE (Positive)
    public function test_admin_bisa_update_metode_pembayaran()
    {
        $this->loginAsAdmin();
        $metode = $this->createMetode();
        $data = \App\Models\MetodePembayaranModel::factory()->create([
            'metode_id' => $metode->metode_id
        ]);
        $update = $this->validData(['kode_bayar' => 'KODE-UPDATE', 'metode_id' => $metode->metode_id]);
        $response = $this->put('/metode-pembayaran/' . $data->metode_pembayaran_id, $update, ['Accept' => 'text/html']);
        if ($response->status() === 500) {
            dump($response->getContent());
        }
        $response->assertRedirect('/metode-pembayaran');
        $this->assertDatabaseHas('t_metode_pembayaran', [
            'metode_pembayaran_id' => $data->metode_pembayaran_id,
            'kode_bayar' => 'KODE-UPDATE',
        ]);
    }

    // UPDATE (Negative)
    public function test_admin_tidak_bisa_update_metode_pembayaran_dengan_data_tidak_valid()
    {
        $this->loginAsAdmin();
        $metode = $this->createMetode();
        $data = \App\Models\MetodePembayaranModel::factory()->create([
            'metode_id' => $metode->metode_id
        ]);
        $update = $this->validData(['nama_pembayaran' => '', 'metode_id' => $metode->metode_id]);
    $response = $this->put('/metode-pembayaran/' . $data->metode_pembayaran_id, $update, ['Accept' => 'text/html']);
    $response->assertSessionHasErrors('nama_pembayaran');
    }

    // DELETE (Positive)
    public function test_admin_bisa_menghapus_metode_pembayaran()
    {
        $this->loginAsAdmin();
        $metode = $this->createMetode();
        $data = \App\Models\MetodePembayaranModel::factory()->create([
            'metode_id' => $metode->metode_id
        ]);
        $response = $this->delete('/metode-pembayaran/' . $data->metode_pembayaran_id, [], ['Accept' => 'text/html']);
        $response->assertRedirect('/metode-pembayaran');
        $this->assertDatabaseMissing('t_metode_pembayaran', [
            'metode_pembayaran_id' => $data->metode_pembayaran_id,
        ]);
    }

    // DELETE (Negative)
    public function test_admin_tidak_bisa_menghapus_metode_pembayaran_yang_tidak_ada()
    {
        $this->loginAsAdmin();
    $response = $this->delete('/metode-pembayaran/999999', [], ['Accept' => 'text/html']);
    $response->assertStatus(404);
    }
}
