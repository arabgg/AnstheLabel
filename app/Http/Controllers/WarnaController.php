<?php

namespace App\Http\Controllers;

use App\Models\WarnaModel;
use Illuminate\Http\Request;

class WarnaController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');
        $sortColumn = $request->input('sort', 'created_at'); // default sorting
        $sortDirection = $request->input('direction', 'desc');

        $warna = WarnaModel::select('warna_id', 'nama_warna', 'kode_hex', 'created_at', 'updated_at')
        ->when(!empty($searchQuery), function($q) use ($searchQuery) {
                $q->where('nama_warna', 'like', "%{$searchQuery}%");
            })
            ->orderBy($sortColumn, $sortDirection)
            ->paginate(10);

        return view('admin.warna.index', compact('warna', 'searchQuery', 'sortColumn', 'sortDirection'));
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
        $warna = WarnaModel::findOrFail($id);
        $warna->delete();

        return response()->json([
            'success' => true,
            'message' => 'Warna berhasil dihapus',
            'id' => $id
        ]);
    }
}
