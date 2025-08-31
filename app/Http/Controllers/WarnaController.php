<?php

namespace App\Http\Controllers;

use App\Models\WarnaModel;
use Illuminate\Http\Request;

class WarnaController extends Controller
{
    public function index()
    {
        $warna = WarnaModel::all();
        return view('admin.warna.index', compact('warna'));
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
        return redirect()->route('admin.warna.index')->with('success', 'Warna berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $warna = WarnaModel::findOrFail($id);
        return view('admin.warna.show', compact('warna'));
    }

    public function edit(string $id)
    {
        $warna = WarnaModel::findOrFail($id);
        return view('admin.warna.edit', compact('warna'));
    }

    
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode_hex' => 'required|string|max:7',
            'nama_warna' => 'required|string|max:255',
        ]);
        $warna = WarnaModel::findOrFail($id);
        $warna->update($request->all());
        return redirect()->route('warna.index')->with('success', 'Warna berhasil diperbarui!');
    }
    
    public function destroy(string $id)
    {
        $warna = WarnaModel::findOrFail($id);
        $warna->delete();
        return redirect()->route('warna.index')->with('success', 'Warna berhasil dihapus!');
    }
}
