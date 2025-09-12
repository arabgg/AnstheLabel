<?php

namespace App\Http\Controllers;

use App\Models\WarnaModel;
use Illuminate\Http\Request;

class WarnaController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');
        $sort = $request->input('sort', 'terbaru'); // default sorting

        $warna = WarnaModel::select('warna_id', 'nama_warna', 'kode_hex', 'created_at', 'updated_at')
            ->when(!empty($searchQuery), function ($q) use ($searchQuery) {
                $q->where(function ($sub) use ($searchQuery) {
                    $sub->where('nama_warna', 'like', "%{$searchQuery}%")
                        ->orWhere('kode_hex', 'like', "%{$searchQuery}%");
                });
            })
            ->when($sort === 'terbaru', function ($q) {
                $q->orderBy('created_at', 'desc');
            })
            ->when($sort === 'terlama', function ($q) {
                $q->orderBy('created_at', 'asc');
            })
            ->paginate(10)
            ->withQueryString(); // supaya search tetap terbawa saat sorting

        return view('admin.warna.index', compact('warna', 'searchQuery', 'sort'));
    }

    public function create()
    {
        return view('admin.warna.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_hex' => 'required|string|max:7',
            'nama_warna' => 'required|string|max:255',
        ]);
        WarnaModel::create($request->all());
        return redirect()->route('warna.index')->with('success', 'Warna berhasil ditambahkan!');
    }

    public function show($id)
    {
        $warna = WarnaModel::select('warna_id', 'nama_warna', 'kode_hex')
            ->findOrFail($id);

        if (request()->ajax()) {
            return view('admin.warna.show', compact('warna'));
        }

        return redirect()->route('warna.index');
    }

    public function edit(string $id)
    {
        $warna = warnaModel::select('warna_id', 'nama_warna', 'kode_hex')
            ->findOrFail($id);

        return view('admin.warna.edit', compact('warna'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_warna' => 'required|string|max:255',
            'kode_hex' => 'nullable|string|max:50',
        ]);

        $warna = warnaModel::select('warna_id', 'nama_warna', 'kode_hex')
            ->findOrFail($id);
        $warna->nama_warna = $request->nama_warna;
        $warna->kode_hex = $request->kode_hex;
        $warna->save();

        return response()->json([
            'success' => true,
            'message' => 'Warna berhasil diperbarui',
            'data' => $warna
        ]);
    }

    public function destroy($id)
    {
        try {
            $warna = WarnaModel::findOrFail($id);
            $warna->delete();

            return response()->json([
                'success' => true,
                'message' => 'Warna berhasil dihapus',
                'id' => $id
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Warna tidak bisa dihapus karena masih digunakan pada produk.'
            ], 400);
        }
    }
}
