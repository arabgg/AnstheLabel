<?php

namespace App\Http\Controllers;

use App\Models\EkspedisiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EkspedisiController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');
        $sort = $request->input('sort', 'terbaru');
        $ekspedisi = EkspedisiModel::select(
            'ekspedisi_id',
            'nama_ekspedisi',
            'status_ekspedisi',
            'created_at',
            'updated_at'
        )
            ->when(!empty($searchQuery), function ($q) use ($searchQuery) {
                $q->where('nama_ekspedisi', 'like', "%{$searchQuery}%");
            })
            ->when($sort === 'terbaru', function ($q) {
                $q->orderBy('created_at', 'desc');
            })
            ->when($sort === 'terlama', function ($q) {
                $q->orderBy('created_at', 'asc');
            })
            ->paginate(10)
            ->appends($request->query());

        return view('admin.ekspedisi.index', compact('ekspedisi', 'searchQuery', 'sort'));
    }

    public function create()
    {
        $ekspedisi = EkspedisiModel::all();
        return view('admin.ekspedisi.create', compact('ekspedisi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ekspedisi' => 'required|string|max:255',
            'status_ekspedisi' => 'required|in:0,1',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            $fileName = $request->file('icon')->hashName();
            $request->file('icon')->storeAs('icons', $fileName, 'public');
            $validated['icon'] = $fileName;
        }

        $ekspedisi = EkspedisiModel::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ekspedisi berhasil ditambahkan',
                'data'    => $ekspedisi
            ]);
        }

        return redirect()->route('ekspedisi.index')->with('success', 'Ekspedisi berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $ekspedisi = EkspedisiModel::findOrFail($id);
        return view('admin.ekspedisi.show', compact('ekspedisi'));
    }


    public function edit(string $id)
    {
        $ekspedisi = EkspedisiModel::findOrFail($id);
        return view('admin.ekspedisi.edit', compact('ekspedisi'));
    }


    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_ekspedisi' => 'required|string|max:255',
            'status_ekspedisi' => 'required|in:0,1',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $ekspedisi = EkspedisiModel::findOrFail($id);

        $ekspedisi->nama_ekspedisi = $validated['nama_ekspedisi'];
        $ekspedisi->status_ekspedisi = $validated['status_ekspedisi'];

        // Update icon
        if ($request->hasFile('icon')) {
            // Hapus file lama jika ada
            if ($ekspedisi->icon && Storage::exists('public/icons/' . $ekspedisi->icon)) {
                Storage::delete('public/icons/' . $ekspedisi->icon);
            }

            $fileName = $request->file('icon')->hashName();
            $request->file('icon')->storeAs('icons', $fileName, 'public');
            $ekspedisi->icon = $fileName;
        } else {
            $ekspedisi->icon = $request->old_icon;
        }

        $ekspedisi->save();

        return response()->json([
            'success' => true,
            'message' => 'Ekspedisi berhasil diperbarui.',
            'data' => $ekspedisi
        ]);
    }

    public function destroy($id)
    {
        try {
            $ekspedisi = EkspedisiModel::findOrFail($id);

            DB::beginTransaction();

            // Hapus data di database
            $ekspedisi->delete();

            DB::commit();

            // Setelah berhasil commit, baru hapus file icon
            if ($ekspedisi->icon && Storage::disk('public')->exists('icons/' . $ekspedisi->icon)) {
                Storage::disk('public')->delete('icons/' . $ekspedisi->icon);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ekspedisi berhasil dihapus.',
                'id' => $id
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Ekspedisi tidak bisa dihapus karena masih digunakan pada data pesanan.'
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
