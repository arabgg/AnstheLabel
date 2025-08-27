<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ProdukModel;
use App\Models\KategoriModel;
use Illuminate\Support\Facades\Cache;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function landing_page_menampilkan_data_dengan_sukses()
    {
        // Buat data dummy
        ProdukModel::factory()->count(4)->create();
        KategoriModel::factory()->count(3)->create();

        // Clear cache untuk memastikan data fresh
        Cache::flush();

        $resp = $this->get(route('home'));

        $resp->assertStatus(200);
        $resp->assertViewIs('home.landingpage.index');
        $resp->assertViewHas(['newarrival', 'bestseller', 'viscose']);
        $resp->assertSeeText('New Arrival');
        $resp->assertSeeText('Best Seller');
    }

    /** @test */
    public function collection_page_menampilkan_data_dengan_filter()
    {
        // Buat data dummy
        $kategori = KategoriModel::factory()->create();
        ProdukModel::factory()->count(5)->create(['kategori_id' => $kategori->kategori_id]);
        ProdukModel::factory()->count(3)->create();

        // Test tanpa filter
        $resp = $this->get(route('collection'));

        $resp->assertStatus(200);
        $resp->assertViewIs('home.collection.index');
        $resp->assertViewHas(['produk', 'kategori']);

        // Test dengan filter kategori
        $resp = $this->get(route('collection', ['filter' => [$kategori->kategori_id]]));

        $resp->assertStatus(200);
        $resp->assertViewHas('produk', function ($produk) use ($kategori) {
            return $produk->every(function ($item) use ($kategori) {
                return $item->kategori_id == $kategori->kategori_id;
            });
        });
    }

    /** @test */
    public function collection_page_menampilkan_data_dengan_pencarian()
    {
        // Buat data dummy dengan nama spesifik
        $produkSpesifik = ProdukModel::factory()->create(['nama_produk' => 'Hijab Pashmina Exclusive']);
        ProdukModel::factory()->count(3)->create();

        // Test dengan pencarian
        $resp = $this->get(route('collection', ['search' => 'Pashmina']));

        $resp->assertStatus(200);
        $resp->assertViewHas('produk', function ($produk) use ($produkSpesifik) {
            return $produk->contains('produk_id', $produkSpesifik->produk_id);
        });
    }

    /** @test */
    public function about_page_menampilkan_data_dengan_sukses()
    {
        ProdukModel::factory()->count(4)->create();

        $resp = $this->get(route('about'));

        $resp->assertStatus(200);
        $resp->assertViewIs('home.about.index');
        $resp->assertViewHas('rekomendasi');
    }

    /** @test */
    public function detail_produk_page_menampilkan_data_dengan_sukses()
    {
        $produk = ProdukModel::factory()->create();

        $resp = $this->get(route('detail.show', $produk->produk_id));

        $resp->assertStatus(200);
        $resp->assertViewIs('home.detail.index');
        $resp->assertViewHas(['produk', 'rekomendasi']);
        $resp->assertSeeText($produk->nama_produk);
    }

    /** @test */
    public function detail_produk_page_gagal_untuk_produk_tidak_ada()
    {
        $resp = $this->get(route('detail.show', 'invalid-id'));

        $resp->assertStatus(404);
    }

    /** @test */
    public function invoice_page_menampilkan_form_dengan_sukses()
    {
        $resp = $this->get(route('invoice'));

        $resp->assertStatus(200);
        $resp->assertViewIs('home.checkout.invoice');
    }

    /** @test */
    public function cek_invoice_sukses_untuk_kode_valid()
    {
        $transaksi = \App\Models\TransaksiModel::factory()->create();

        $resp = $this->post(route('invoice.cek'), [
            'kode_invoice' => $transaksi->kode_invoice
        ]);

        $resp->assertRedirect(route('transaksi.show', $transaksi->kode_invoice));
    }

    /** @test */
    public function cek_invoice_gagal_untuk_kode_tidak_valid()
    {
        $resp = $this->post(route('invoice.cek'), [
            'kode_invoice' => 'INVALID-CODE'
        ]);

        $resp->assertRedirect();
        $resp->assertSessionHas('error', 'Kode Invoice tidak ditemukan');
    }

    /** @test */
    public function transaksi_page_menampilkan_data_dengan_sukses()
    {
        $transaksi = \App\Models\TransaksiModel::factory()->create();

        $resp = $this->get(route('transaksi.show', $transaksi->kode_invoice));

        $resp->assertStatus(200);
        $resp->assertViewIs('home.checkout.transaksi');
        $resp->assertViewHas('transaksi');
    }
}
