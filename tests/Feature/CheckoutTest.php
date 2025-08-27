<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ProdukModel;
use App\Models\WarnaModel;
use App\Models\UkuranModel;
use App\Models\TransaksiModel;
use App\Models\PembayaranModel;
use App\Models\MetodeModel;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cart_page_menampilkan_data_dengan_sukses()
    {
        // Simulasi keranjang
        $cart = [
            [
                'produk_id' => 1,
                'nama' => 'Produk 1',
                'harga' => 100000,
                'quantity' => 2,
                'warna_id' => null,
                'ukuran_id' => null,
            ]
        ];
        session()->put('cart', $cart);

        $resp = $this->get(route('cart.index'));

        $resp->assertStatus(200);
        $resp->assertViewIs('home.cart.index');
        $resp->assertViewHas(['cart', 'total', 'rekomendasi']);
    }

    /** @test */
    public function add_cart_sukses()
    {
        $produk = ProdukModel::factory()->create();
        $warna = WarnaModel::factory()->create();
        $ukuran = UkuranModel::factory()->create();

        $resp = $this->post(route('cart.add'), [
            'produk_id' => $produk->produk_id,
            'warna' => $warna->warna_id,
            'ukuran' => $ukuran->ukuran_id,
            'quantity' => 2,
            'action' => 'add'
        ]);

        $resp->assertJson(['success' => true]);
        $this->assertNotEmpty(session('cart'));
    }

    /** @test */
    public function update_cart_sukses()
    {
        // Simulasi keranjang
        $cart = [
            [
                'produk_id' => 1,
                'nama' => 'Produk 1',
                'harga' => 100000,
                'quantity' => 2,
                'warna_id' => null,
                'ukuran_id' => null,
            ]
        ];
        session()->put('cart', $cart);

        $resp = $this->post(route('cart.update'), [
            'index' => 0,
            'quantity' => 3
        ]);

        $resp->assertRedirect(route('cart.index'));
        $this->assertEquals(3, session('cart')[0]['quantity']);
    }

    /** @test */
    public function remove_cart_sukses()
    {
        // Simulasi keranjang
        $cart = [
            [
                'produk_id' => 1,
                'nama' => 'Produk 1',
                'harga' => 100000,
                'quantity' => 2,
                'warna_id' => null,
                'ukuran_id' => null,
            ]
        ];
        session()->put('cart', $cart);

        $resp = $this->post(route('cart.remove'), [
            'index' => 0
        ]);

        $resp->assertRedirect(route('cart.index'));
        $this->assertEmpty(session('cart'));
    }

    /** @test */
    public function checkout_page_menampilkan_form_dengan_sukses()
    {
        // Simulasi keranjang
        $cart = [
            [
                'produk_id' => 1,
                'nama' => 'Produk 1',
                'harga' => 100000,
                'quantity' => 2,
                'warna_id' => null,
                'ukuran_id' => null,
            ]
        ];
        session()->put('cart', $cart);

        $resp = $this->get(route('checkout.form'));

        $resp->assertStatus(200);
        $resp->assertViewIs('home.checkout.form');
        $resp->assertViewHas(['cart', 'total']);
    }

    /** @test */
    public function save_checkout_sukses()
    {
        // Simulasi keranjang
        $cart = [
            [
                'produk_id' => 1,
                'nama' => 'Produk 1',
                'harga' => 100000,
                'quantity' => 2,
                'warna_id' => null,
                'ukuran_id' => null,
            ]
        ];
        session()->put('cart', $cart);

        $resp = $this->post(route('checkout.save'), [
            'nama' => 'John Doe',
            'telepon' => '08123456789',
            'email' => 'john@example.com',
            'alamat' => 'Jl. Contoh No. 1',
            'kota' => 'Jakarta',
            'kecamatan' => 'Kecamatan A',
        ]);

        $resp->assertRedirect(route('checkout.payment'));
        $this->assertNotNull(session('checkout_data'));
    }

    /** @test */
    public function payment_page_menampilkan_form_dengan_sukses()
    {
        // Simulasi keranjang
        $cart = [
            [
                'produk_id' => 1,
                'nama' => 'Produk 1',
                'harga' => 100000,
                'quantity' => 2,
                'warna_id' => null,
                'ukuran_id' => null,
            ]
        ];
        session()->put('cart', $cart);

        // Simulasi data checkout
        session()->put('checkout_data', [
            'nama' => 'John Doe',
            'telepon' => '08123456789',
            'email' => 'john@example.com',
            'alamat' => 'Jl. Contoh No. 1',
        ]);

        MetodeModel::factory()->count(2)->create();

        $resp = $this->get(route('checkout.payment'));

        $resp->assertStatus(200);
        $resp->assertViewIs('home.checkout.payment');
        $resp->assertViewHas(['cart', 'checkoutData', 'paymentMethods', 'total']);
    }

    /** @test */
    public function process_payment_sukses()
    {
        // Simulasi keranjang
        $cart = [
            [
                'produk_id' => 1,
                'nama' => 'Produk 1',
                'harga' => 100000,
                'quantity' => 2,
                'warna_id' => null,
                'ukuran_id' => null,
            ]
        ];
        session()->put('cart', $cart);

        // Simulasi data checkout
        session()->put('checkout_data', [
            'nama' => 'John Doe',
            'telepon' => '08123456789',
            'email' => 'john@example.com',
            'alamat' => 'Jl. Contoh No. 1',
        ]);

        $metode = MetodeModel::factory()->create();

        $resp = $this->post(route('checkout.process'), [
            'metode_id' => $metode->metode_id,
        ]);

        $resp->assertRedirect(route('cart.index'));
        $this->assertNull(session('cart'));
        $this->assertNull(session('checkout_data'));
        
        // Verifikasi data tersimpan di database
        $this->assertDatabaseHas('t_transaksi', [
            'nama_customer' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }
}
