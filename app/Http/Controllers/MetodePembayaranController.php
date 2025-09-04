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
            ->orderBy('metode_pembayaran_id', 'asc')
            ->paginate(10);
            
        return view('admin.metode_pembayaran.index', compact('metode', 'searchQuery'));
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
        $metode = MetodePembayaranModel::select('metode_pembayaran_id', 'metode_id', 'nama_pembayaran', 'kode_bayar', 'icon')
            ->with('metode:metode_id,nama_metode')
            ->findOrFail($id);
        
        if (request()->ajax()) {
            return view('admin.metode_pembayaran.show', compact('metode'));
        }

        return redirect()->route('metode_pembayaran.index');
    }

    public function edit(string $id)
    {
        $metode = MetodePembayaranModel::select('metode_pembayaran_id', 'metode_id', 'nama_pembayaran', 'kode_bayar', 'icon')
            ->with('metode:metode_id,nama_metode')
            ->findOrFail($id);

        $metodes = MetodeModel::select('metode_id', 'nama_metode')->get();

        return view('admin.metode_pembayaran.edit', compact('metode', 'metodes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pembayaran' => 'required|string|max:255',
            'metode_id' => 'required|string|exists:m_metode_pembayaran,metode_id',
            'kode_bayar' => 'nullable|string|max:255',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $metode = MetodePembayaranModel::findOrFail($id);

        $metode->nama_pembayaran = $request->nama_pembayaran;
        $metode->metode_id = $request->metode_id;

        // Update kode_bayar
        if($request->hasFile('kode_bayar_img')){
            $path = $request->file('kode_bayar_img')->store('icons', 'public');
            $metode->kode_bayar = basename($path);
        } else if($request->kode_bayar){
            $metode->kode_bayar = $request->kode_bayar;
        }

        // Update icon
        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('icons', 'public');
            $metode->icon = basename($path);
        } else {
            $metode->icon = $request->old_icon;
        }

        $metode->save();

        return response()->json([
            'success' => true,
            'message' => 'Metode pembayaran berhasil diperbarui',
            'data' => $metode
        ]);
    }

    public function destroy($id)
    {
        $ukuran = MetodePembayaranModel::findOrFail($id);
        $ukuran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Metode pembayaran berhasil dihapus',
            'id' => $id
        ]);
    }
}
