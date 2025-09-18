<?php

namespace App\Http\Controllers;

use App\Models\UkuranModel;
use Illuminate\Http\Request;

class UkuranController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');
        $sort = $request->input('sort', 'terbaru'); // default sorting

        $ukuran = UkuranModel::select('ukuran_id', 'nama_ukuran', 'deskripsi', 'created_at', 'updated_at')
            ->when(!empty($searchQuery), function ($q) use ($searchQuery) {
                $q->where('nama_ukuran', 'like', "%{$searchQuery}%");
            })
            ->when($sort === 'terbaru', function ($q) {
                $q->orderBy('created_at', 'desc');
            })
            ->when($sort === 'terlama', function ($q) {
                $q->orderBy('created_at', 'asc');
            })
            ->paginate(10)
            ->withQueryString(); // supaya search tetap terbawa saat sorting

        return view('admin.ukuran.index', compact('ukuran', 'searchQuery', 'sort'));
    }

    public function create()
    {
        return view('admin.ukuran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ukuran' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $ukuran = UkuranModel::create($validated);
        
        // Balas JSON kalau request dari AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ukuran berhasil ditambahkan',
                'data'    => $ukuran
            ]);
        }

        // Kalau bukan AJAX, fallback ke redirect
        return redirect()->route('ukuran.index')->with('success', 'Ukuran berhasil ditambahkan!');
    }

    public function show($id)
    {
        $ukuran = UkuranModel::select('ukuran_id', 'nama_ukuran', 'deskripsi')
            ->findOrFail($id);

        if (request()->ajax()) {
            return view('admin.ukuran.show', compact('ukuran'));
        }

        return redirect()->route('ukuran.index');
    }

    public function edit(string $id)
    {
        $ukuran = UkuranModel::select('ukuran_id', 'nama_ukuran', 'deskripsi')
            ->findOrFail($id);

        return view('admin.ukuran.edit', compact('ukuran'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ukuran' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $ukuran = UkuranModel::select('ukuran_id', 'nama_ukuran', 'deskripsi')
            ->findOrFail($id);
        $ukuran->nama_ukuran = $request->nama_ukuran;
        $ukuran->deskripsi = $request->deskripsi;
        $ukuran->save();

        return response()->json([
            'success' => true,
            'message' => 'Ukuran berhasil diperbarui',
            'data' => $ukuran
        ]);
    }

    public function destroy($id)
    {
        try {
            $ukuran = UkuranModel::findOrFail($id);
            $ukuran->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ukuran berhasil dihapus',
                'id'      => $id
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ukuran tidak bisa dihapus karena masih digunakan pada produk.'
            ], 400);
        }
    }
}
