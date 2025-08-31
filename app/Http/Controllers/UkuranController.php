<?php

namespace App\Http\Controllers;

use App\Models\UkuranModel;
use Illuminate\Http\Request;

class UkuranController extends Controller
{
    public function index()
    {
        $ukuran = UkuranModel::all();
        return view('admin.ukuran.index', compact('ukuran'));
    }

    public function create()
    {
        return view('admin.ukuran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ukuran' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        UkuranModel::create($request->all());

        return redirect()->route('ukuran.index')->with('success', 'Ukuran berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $ukuran = UkuranModel::findOrFail($id);
        return view('admin.ukuran.show', compact('ukuran'));
    }

    public function edit(string $id)
    {
        $ukuran = UkuranModel::findOrFail($id);
        return view('admin.ukuran.edit', compact('ukuran'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_ukuran' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $ukuran = UkuranModel::findOrFail($id);
        $ukuran->update($request->all());

        return redirect()->route('admin.ukuran.index')->with('success', 'Ukuran berhasil diupdate!');
    }

    public function destroy(string $id)
    {
        $ukuran = UkuranModel::findOrFail($id);
        $ukuran->delete();

        return redirect()->route('admin.ukuran.index')->with('success', 'Ukuran berhasil dihapus!');
    }
}
