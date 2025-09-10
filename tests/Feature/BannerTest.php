<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class BannerTest extends TestCase
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

    private function createBanner($overrides = [])
    {
        return \App\Models\BannerModel::factory()->create($overrides);
    }

    public function test_admin_bisa_melihat_daftar_banner()
    {
        $this->loginAsAdmin();
        $response = $this->get(route('banner.index'));
        $response->assertStatus(200);
        $response->assertViewHas('banners');
    }

    public function test_admin_bisa_melihat_detail_banner()
    {
        $this->loginAsAdmin();
        $banner = $this->createBanner();
        $response = $this->get(route('banner.show', ['id' => $banner->banner_id]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);
        $response->assertStatus(200);
        $response->assertViewHas('banner');
    }

    public function test_admin_bisa_melihat_form_edit_banner()
    {
        $this->loginAsAdmin();
        $banner = $this->createBanner();
        $response = $this->get(route('banner.edit', ['id' => $banner->banner_id]));
        $response->assertStatus(200);
        $response->assertViewHas('banner');
    }

    public function test_admin_bisa_update_banner_dengan_file_valid()
    {
        $this->loginAsAdmin();
        $banner = $this->createBanner();
        $file = UploadedFile::fake()->image('banner.jpg');
        $response = $this->put(route('banner.update', ['id' => $banner->banner_id]), [
            'foto_banner' => $file,
        ]);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('m_banner', [
            'banner_id' => $banner->banner_id,
        ]);
    }

    public function test_admin_tidak_bisa_update_banner_dengan_file_tidak_valid()
    {
        $this->loginAsAdmin();
        $banner = $this->createBanner();
        $file = UploadedFile::fake()->create('banner.txt', 100); // file tidak valid
        $response = $this->put(route('banner.update', ['id' => $banner->banner_id]), [
            'foto_banner' => $file,
        ]);
        $response->assertSessionHasErrors('foto_banner');
    }

    public function test_admin_tidak_bisa_update_banner_tanpa_file()
    {
        $this->loginAsAdmin();
        $banner = $this->createBanner();
        $response = $this->put(route('banner.update', ['id' => $banner->banner_id]), []);
        $response->assertSessionHasErrors('foto_banner');
    }
}
