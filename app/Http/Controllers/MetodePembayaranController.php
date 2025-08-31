<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaranModel;
use Illuminate\Http\Request;

class MetodePembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = MetodePembayaranModel::with('metode')->paginate(10);
        return view('metode-pembayaran.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('metode-pembayaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'metode_id' => 'required|integer',
            'nama_pembayaran' => 'required|string|max:255',
            'kode_bayar' => 'required|string|max:50|unique:t_metode_pembayaran,kode_bayar',
            'status_pembayaran' => 'required|boolean',
            'icon' => 'nullable|string|max:255',
        ]);

        MetodePembayaranModel::create($validated);

        return redirect()->route('metode-pembayaran.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $metode = MetodePembayaranModel::with('metode', 'pembayaran')->findOrFail($id);
        return view('metode-pembayaran.show', compact('metode'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $metode = MetodePembayaranModel::findOrFail($id);
        return view('metode-pembayaran.edit', compact('metode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $metode = MetodePembayaranModel::findOrFail($id);

        $validated = $request->validate([
            'metode_id' => 'required|integer',
            'nama_pembayaran' => 'required|string|max:255',
            'kode_bayar' => 'required|string|max:50|unique:t_metode_pembayaran,kode_bayar,' . $id . ',metode_pembayaran_id',
            'status_pembayaran' => 'required|boolean',
            'icon' => 'nullable|string|max:255',
        ]);

        $metode->update($validated);

        return redirect()->route('metode-pembayaran.index')
            ->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $metode = MetodePembayaranModel::findOrFail($id);
        $metode->delete();

        return redirect()->route('metode-pembayaran.index')
            ->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}
