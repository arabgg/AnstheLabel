<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaranModel;
use App\Models\MetodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MetodePembayaranController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');

        $metode = MetodePembayaranModel::select('metode_pembayaran_id', 'metode_id', 'nama_pembayaran', 'kode_bayar', 'icon', 'created_at', 'updated_at')
            ->with('metode:metode_id,nama_metode')
            ->when(!empty($searchQuery), function($q) use ($searchQuery) {
                $q->where('nama_pembayaran', 'like', "%{$searchQuery}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.metode-pembayaran.index', compact('metode', 'searchQuery'));
    }

    public function create()
    {
        $metodes = MetodeModel::all();
        return view('admin.metode_pembayaran.create', compact('metodes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'metode_id' => 'required|integer',
            'nama_pembayaran' => 'required|string|max:255',
            'kode_bayar' => 'required|string|max:50|unique:t_metode_pembayaran,kode_bayar',
            'status_pembayaran' => 'required|boolean',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            $fileName = time() . '_' . $request->file('icon')->getClientOriginalName();
            $request->file('icon')->storeAs('icons', $fileName, 'public');
            $validated['icon'] = $fileName;
        }


        MetodePembayaranModel::create($validated);

        return redirect()->route('metode_pembayaran.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function show($id)
    {
        $metode = MetodePembayaranModel::with('metode', 'pembayaran')->findOrFail($id);
        return view('admin.metode_pembayaran.show', compact('metode'));
    }

    public function edit($id)
    {
        $mp = MetodePembayaranModel::findOrFail($id);
        $metodes = MetodeModel::all();

        return view('admin.metode_pembayaran.edit', compact('mp', 'metodes'));
    }

    public function update(Request $request, $id)
    {
        $mp = MetodePembayaranModel::findOrFail($id);

        $validated = $request->validate([
            'metode_id' => 'required|integer',
            'nama_pembayaran' => 'required|string|max:255',
            'kode_bayar' => 'required|string|max:50|unique:t_metode_pembayaran,kode_bayar,' . $id . ',metode_pembayaran_id',
            'status_pembayaran' => 'required|in:1,0',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            if ($mp->icon && Storage::disk('public')->exists('icons/' . $mp->icon)) {
                Storage::disk('public')->delete('icons/' . $mp->icon);
            }
            $fileName = time() . '_' . $request->file('icon')->getClientOriginalName();
            $request->file('icon')->storeAs('icons', $fileName, 'public');
            $validated['icon'] = $fileName;
        }

        $mp->update($validated);

        return redirect()->route('metode_pembayaran.index')
            ->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

public function destroy($id)
{
    $mp = MetodePembayaranModel::findOrFail($id);

    $filePath = 'icons/' . $mp->icon;

    if ($mp->icon && Storage::disk('public')->exists($filePath)) {
        Storage::disk('public')->delete($filePath);
    }

    $mp->delete();

    return redirect()->route('metode_pembayaran.index')
        ->with('success', 'Metode pembayaran berhasil dihapus beserta icon.');
}
}
