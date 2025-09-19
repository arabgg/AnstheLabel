<?php

namespace App\Http\Controllers;

use App\Models\BahanModel;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');
        $sort = $request->input('sort', 'terbaru');

        $bahan = BahanModel::select('bahan_id', 'nama_bahan', 'deskripsi', 'created_at', 'updated_at')
            ->when(!empty($searchQuery), function ($q) use ($searchQuery) {
                $q->where('nama_bahan', 'like', "%{$searchQuery}%");
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
            ->withQueryString(); // supaya search tetap terbawa saat sorting

        return view('admin.bahan.index', compact('bahan', 'searchQuery', 'sort'));
    }

    public function create()
    {
        return view('admin.bahan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bahan' => 'required|string|max:100',
            'deskripsi' => 'required|string',
        ]);

        $bahan = BahanModel::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Bahan berhasil ditambahkan',
                'data'    => $bahan
            ]);
        }

        return redirect()->route('bahan.index')
            ->with('success', 'Bahan berhasil ditambahkan');
    }

    public function show($id)
    {
        $bahan = BahanModel::select('bahan_id', 'nama_bahan', 'deskripsi')
            ->findOrFail($id);

        if (request()->ajax()) {
            return view('admin.bahan.show', compact('bahan'));
        }

        return redirect()->route('bahan.index');
    }

    public function edit(string $id)
    {
        $bahan = BahanModel::select('bahan_id', 'nama_bahan', 'deskripsi')
            ->findOrFail($id);

        return view('admin.bahan.edit', compact('bahan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $bahan = BahanModel::select('bahan_id', 'nama_bahan', 'deskripsi')
            ->findOrFail($id);
        $bahan->nama_bahan = $request->nama_bahan;
        $bahan->deskripsi = $request->deskripsi;
        $bahan->save();

        return response()->json([
            'success' => true,
            'message' => 'Bahan berhasil diperbarui',
            'data' => $bahan
        ]);
    }

    public function destroy($id)
    {
        try {
            $bahan = BahanModel::findOrFail($id);
            $bahan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bahan berhasil dihapus',
                'id'      => $id
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bahan tidak bisa dihapus karena masih digunakan pada produk.'
            ], 400);
        }
    }
}
