<?php

namespace App\Http\Controllers;

use App\Models\UkuranModel;
use Illuminate\Http\Request;

class UkuranController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');
        $sortColumn = $request->input('sort', 'created_at'); // default sorting
        $sortDirection = $request->input('direction', 'desc');
        $ukuran = UkuranModel::select('ukuran_id', 'nama_ukuran', 'deskripsi', 'created_at', 'updated_at')
            ->when(!empty($searchQuery), function ($q) use ($searchQuery) {
                $q->where('nama_ukuran', 'like', "%{$searchQuery}%");
            })
            ->orderBy($sortColumn, $sortDirection)
            ->paginate(10);

        return view('admin.ukuran.index', compact('ukuran', 'searchQuery', 'sortColumn', 'sortDirection'));
    }

    public function create()
    {
        return view('admin.ukuran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ukuran' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        UkuranModel::create($request->all());

        return redirect()->route('ukuran.index')->with('success', 'Ukuran berhasil ditambahkan!');
    }

    public function show($id)
    {
        $ukuran = UkuranModel::select('ukuran_id', 'nama_ukuran', 'deskripsi')
            ->findOrFail($id);

        if (request()->ajax()) {
            return view('admin.ukuran.show', compact('ukuran'));
        }

        return redirect()->route('ukuran.index');
    }

    public function edit(string $id)
    {
        $ukuran = UkuranModel::select('ukuran_id', 'nama_ukuran', 'deskripsi')
            ->findOrFail($id);

        return view('admin.ukuran.edit', compact('ukuran'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ukuran' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $ukuran = UkuranModel::select('ukuran_id', 'nama_ukuran', 'deskripsi')
            ->findOrFail($id);
        $ukuran->nama_ukuran = $request->nama_ukuran;
        $ukuran->deskripsi = $request->deskripsi;
        $ukuran->save();

        return response()->json([
            'success' => true,
            'message' => 'Ukuran berhasil diperbarui',
            'data' => $ukuran
        ]);
    }

    public function destroy($id)
    {
        $ukuran = UkuranModel::findOrFail($id);
        $ukuran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ukuran berhasil dihapus',
            'id' => $id
        ]);
    }
}
