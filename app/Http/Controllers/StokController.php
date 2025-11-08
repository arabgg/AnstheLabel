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
        $status = $request->input('status', '');

        $produk = ProdukModel::select('produk_id', 'nama_produk', 'stok_produk', 'created_at', 'updated_at')
            ->when(!empty($searchQuery), function ($q) use ($searchQuery) {
                $q->where('nama_produk', 'like', "%{$searchQuery}%");
            })
            ->when($status === 'aman', function ($q) {
                $q->where('stok_produk', '>', 5);
            })
            ->when($status === 'mulai', function ($q) {
                $q->whereBetween('stok_produk', [4, 5]);
            })
            ->when($status === 'habis', function ($q) {
                $q->where('stok_produk', '<', 4);
            })
            ->when($sort === 'stok_desc', function ($q) {
                $q->orderBy('stok_produk', 'desc');
            })
            ->when($sort === 'stok_asc', function ($q) {
                $q->orderBy('stok_produk', 'asc');
            })
            // Default sort: stok paling sedikit di atas
            ->when(empty($sort), function ($q) {
                $q->orderBy('stok_produk', 'asc');
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.stok.index', compact('produk', 'searchQuery', 'sort'));
    }
}
