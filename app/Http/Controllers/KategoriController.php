<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');

        $kategori = KategoriModel::select('kategori_id', 'nama_kategori', 'created_at')
        ->when(!empty($searchQuery), function($q) use ($searchQuery) {
                $q->where('nama_kategori', 'like', "%{$searchQuery}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.kategori.index', compact('kategori', 'searchQuery'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.string' => 'Nama kategori harus berupa teks.',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter.',
        ]);

        KategoriModel::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function show($id)
    {
        $kategori = KategoriModel::select('kategori_id', 'nama_kategori')
            ->findOrFail($id);
        
        if (request()->ajax()) {
            return view('admin.kategori.show', compact('kategori'));
        }

        return redirect()->route('kategori.index');
    }

    public function edit($id)
    {
        $kategori = KategoriModel::select('kategori_id', 'nama_kategori')
            ->findOrFail($id);

        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $kategori = KategoriModel::select('kategori_id', 'nama_kategori')
            ->findOrFail($id);
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->save();

        // Selalu kembalikan JSON
        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui',
            'data' => $kategori
        ]);
    }

    public function destroy($id)
    {
        $kategori = KategoriModel::findOrFail($id);
        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus',
            'id' => $id
        ]);
    }
}
