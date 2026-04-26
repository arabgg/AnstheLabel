<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProdukModel;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->limit ?? 10;

        $query = ProdukModel::query()
            ->where('stock', '>', 0);

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->bahan_id) {
            $query->where('bahan_id', $request->bahan_id);
        }

        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->ukuran_id) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('ukuran_id', $request->ukuran_id)
                ->where('stock', '>', 0);
            });
        }

        if ($request->sort == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort == 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate($limit);

        return ApiResponse::success(
            $products->items(),
            'OK',
            [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage()
            ]
        );
    }

    public function show($id)
    {
        $product = ProdukModel::with(['variants', 'photos'])->find($id);

        if (!$product) {
            return ApiResponse::error('Produk tidak ditemukan', 404);
        }

        return ApiResponse::success($product);
    }
}