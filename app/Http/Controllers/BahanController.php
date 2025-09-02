<?php

namespace App\Http\Controllers;

use App\Models\BahanModel;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');

        $bahan = BahanModel::select('bahan_id', 'nama_bahan', 'deskripsi', 'created_at', 'updated_at')
        ->when(!empty($searchQuery), function($q) use ($searchQuery) {
                $q->where('nama_bahan', 'like', "%{$searchQuery}%");
            })
            ->orderBy('bahan_id', 'asc')
            ->paginate(10);
            
        return view('admin.bahan.index', compact('bahan', 'searchQuery'));
    }

    public function create()
    {
        return view('admin.bahan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:100',
            'deskripsi'  => 'nullable|string',
        ]);

        BahanModel::create($request->all());

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
            'deskripsi' => 'nullable|string|max:255',
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
        $bahan = BahanModel::findOrFail($id);
        $bahan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bahan berhasil dihapus',
            'id' => $id
        ]);
    }
}
