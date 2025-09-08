<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\MetodeModel;
use App\Models\MetodePembayaranModel;

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
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    private function createMetode()
    {
        return MetodeModel::factory()->create();
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

    /** @test */
    public function admin_bisa_melihat_daftar_metode_pembayaran()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('metode_pembayaran.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_bisa_menambah_metode_pembayaran()
    {
        $this->loginAsAdmin();

        $data = $this->validData();
        $response = $this->post(route('metode_pembayaran.store'), $data);

        $response->assertRedirect(route('metode_pembayaran.index'));
        $this->assertDatabaseHas('t_metode_pembayaran', [
            'nama_pembayaran' => $data['nama_pembayaran'],
            'kode_bayar' => $data['kode_bayar'],
        ]);
    }

    /** @test */
    public function admin_tidak_bisa_menambah_metode_pembayaran_dengan_data_tidak_valid()
    {
        $this->loginAsAdmin();

        $data = $this->validData(['nama_pembayaran' => '']);
        $response = $this->post(route('metode_pembayaran.store'), $data);

        $response->assertSessionHasErrors('nama_pembayaran');
    }

    /** @test */
    public function admin_bisa_melihat_detail_metode_pembayaran_dengan_ajax()
    {
        $this->loginAsAdmin();

        $metode = $this->createMetode();
        $data = MetodePembayaranModel::factory()->create(['metode_id' => $metode->metode_id]);

        $response = $this->get(
            route('metode_pembayaran.show', $data->metode_pembayaran_id),
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_tidak_bisa_melihat_metode_pembayaran_yang_tidak_ada()
    {
        $this->loginAsAdmin();

        $response = $this->get(
            route('metode_pembayaran.show', 999999),
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(404);
    }

    /** @test */
    public function test_admin_bisa_update_metode_pembayaran()
    {
        $this->actingAs(User::factory()->create());

        $data = MetodePembayaranModel::factory()->create();

        $update = [
            'nama_pembayaran' => 'Metode Update',
            'metode_id' => (string) $data->metode_id, // penting! kirim string biar lolos validasi
            'kode_bayar' => 'KODE-UPDATE',
        ];

        $response = $this->put(
            route('metode_pembayaran.update', $data->metode_pembayaran_id),
            $update,
            ['Accept' => 'application/json'] // supaya respon JSON
        );

        $response->assertStatus(200)
                ->assertJson(['success' => true]);

        $this->assertDatabaseHas('t_metode_pembayaran', [
            'metode_pembayaran_id' => $data->metode_pembayaran_id,
            'nama_pembayaran' => 'Metode Update',
            'kode_bayar' => 'KODE-UPDATE',
        ]);
    }


    /** @test */
    public function admin_tidak_bisa_update_metode_pembayaran_dengan_data_tidak_valid()
    {
        $this->loginAsAdmin();

        $metode = $this->createMetode();
        $data = MetodePembayaranModel::factory()->create(['metode_id' => $metode->metode_id]);

        $update = $this->validData(['nama_pembayaran' => '', 'metode_id' => $metode->metode_id]);

        $response = $this->put(route('metode_pembayaran.update', $data->metode_pembayaran_id), $update);

        $response->assertSessionHasErrors('nama_pembayaran');
    }

    /** @test */
    public function admin_bisa_menghapus_metode_pembayaran()
    {
        $this->loginAsAdmin();

        $metode = $this->createMetode();
        $data = MetodePembayaranModel::factory()->create(['metode_id' => $metode->metode_id]);

        $response = $this->delete(route('metode_pembayaran.destroy', $data->metode_pembayaran_id));

        $response->assertStatus(200) // controller return JSON
                 ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('t_metode_pembayaran', [
            'metode_pembayaran_id' => $data->metode_pembayaran_id,
        ]);
    }

    /** @test */
    public function admin_tidak_bisa_menghapus_metode_pembayaran_yang_tidak_ada()
    {
        $this->loginAsAdmin();

        $response = $this->delete(route('metode_pembayaran.destroy', 999999));
        $response->assertStatus(404);
    }
}
