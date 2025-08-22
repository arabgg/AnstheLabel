<?php

namespace App\Http\Controllers;

use App\Models\BahanModel;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    public function index()
    {
        $bahan = BahanModel::all();
        return view('bahan.index', compact('bahan'));
    }

    public function create()
    {
        return view('bahan.create');
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

    public function show(string $id)
    {
        $bahan = BahanModel::findOrFail($id);
        return view('bahan.show', compact('bahan'));
    }

    public function edit(string $id)
    {
        $bahan = BahanModel::findOrFail($id);
        return view('bahan.edit', compact('bahan'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:100',
            'deskripsi'  => 'nullable|string',
        ]);

        $bahan = BahanModel::findOrFail($id);
        $bahan->update($request->all());

        return redirect()->route('bahan.index')
            ->with('success', 'Bahan berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $bahan = BahanModel::findOrFail($id);
        $bahan->delete();

        return redirect()->route('bahan.index')
            ->with('success', 'Bahan berhasil dihapus');
    }
}
