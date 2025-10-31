<?php

namespace App\Http\Controllers;

use App\Models\ProdukModel;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');
        $sort = $request->input('sort', '');

        $produk = ProdukModel::select('produk_id', 'nama_produk', 'stok_produk','created_at', 'updated_at')
            ->when(!empty($searchQuery), function ($q) use ($searchQuery) {
                $q->where('nama_kategori', 'like', "%{$searchQuery}%");
            })
            ->when($sort === 'terbaru', function ($q) {
                $q->orderBy('created_at', 'desc');
            })
            ->when($sort === 'terlama', function ($q) {
                $q->orderBy('created_at', 'asc');
            })
            ->when($sort === 'terupdate', function ($q) {
                $q->orderBy('updated_at', 'desc');
            })
            ->paginate(10)
            ->withQueryString(); 

        return view('admin.stok.index', compact('produk', 'searchQuery', 'sort'));
    }
}
