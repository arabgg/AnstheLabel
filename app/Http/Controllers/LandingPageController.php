<?php

namespace App\Http\Controllers;

use App\Models\GambarUtamaModel;
use App\Models\HeroModel;
use Illuminate\Http\Request;
use App\Models\ProdukModel;

class LandingPageController extends Controller
{
    public function index() {
        $produk = ProdukModel::all();

        return view('landingpage.index', [
            'produk' => $produk
        ]);
    }
}
