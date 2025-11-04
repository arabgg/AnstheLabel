<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaranModel;
use App\Models\MetodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MetodePembayaranController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');
        $metodeId = $request->input('metode_id', ''); // filter by metode_id
        $sort = $request->input('sort', 'terbaru');   // default sorting terbaru

        $metode = MetodePembayaranModel::select(
            'metode_pembayaran_id',
            'metode_id',
            'nama_pembayaran',
            'kode_bayar',
            'icon',
            'status_pembayaran',
            'atas_nama',
            'updated_at',
            'created_at'
        )
            ->with('metode:metode_id,nama_metode')
            ->when(!empty($searchQuery), function ($q) use ($searchQuery) {
                $q->where('nama_pembayaran', 'like', "%{$searchQuery}%");
            })
            ->when(!empty($metodeId), function ($q) use ($metodeId) {
                $q->where('metode_id', $metodeId);
            })
            ->when($sort === 'terbaru', function ($q) {
                $q->orderBy('created_at', 'desc');
            })
            ->when($sort === 'terlama', function ($q) {
                $q->orderBy('created_at', 'asc');
            })
            ->paginate(10)
            ->appends($request->query()); // supaya filter/sort tetap ada saat pindah halaman

        return view('admin.metode_pembayaran.index', compact('metode', 'searchQuery', 'metodeId', 'sort'));
    }

    public function create()
    {
        $metodes = MetodeModel::all();
        return view('admin.metode_pembayaran.create', compact('metodes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'metode_id' => 'required|integer|exists:m_metode_pembayaran,metode_id',
            'nama_pembayaran' => 'required|string|max:255',
            'kode_bayar' => 'required|string|max:255',
            'atas_nama' => 'required|string|max:255',
            'status_pembayaran' => 'required|in:0,1',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            $fileName = $request->file('icon')->hashName();
            $request->file('icon')->storeAs('icons', $fileName, 'public');
            $validated['icon'] = $fileName;
        }

        $metode = MetodePembayaranModel::create($validated);

        // Kalau request dari AJAX, balas JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Metode pembayaran berhasil ditambahkan',
                'data'    => $metode
            ]);
        }

        // Kalau request biasa (form POST normal), redirect
        return redirect()->route('metode_pembayaran.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function show($id)
    {
        $metode = MetodePembayaranModel::select('metode_pembayaran_id', 'metode_id', 'nama_pembayaran', 'kode_bayar', 'atas_nama', 'status_pembayaran', 'icon')
            ->with('metode:metode_id,nama_metode')
            ->findOrFail($id);

        if (request()->ajax()) {
            return view('admin.metode_pembayaran.show', compact('metode'));
        }

        return redirect()->route('metode_pembayaran.index');
    }

    public function edit(string $id)
    {
        $metode = MetodePembayaranModel::select('metode_pembayaran_id', 'metode_id', 'nama_pembayaran', 'kode_bayar', 'atas_nama', 'status_pembayaran', 'icon')
            ->with('metode:metode_id,nama_metode')
            ->findOrFail($id);

        $metodes = MetodeModel::select('metode_id', 'nama_metode')->get();

        return view('admin.metode_pembayaran.edit', compact('metode', 'metodes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'metode_id' => 'required|integer|exists:m_metode_pembayaran,metode_id',
            'nama_pembayaran' => 'required|string|max:255',
            'kode_bayar' => 'required|string|max:255',
            'atas_nama' => 'required|string|max:255',
            'status_pembayaran' => 'required|in:0,1',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $metode = MetodePembayaranModel::findOrFail($id);

        $metode->nama_pembayaran = $request->nama_pembayaran;
        $metode->metode_id = $request->metode_id;
        $metode->atas_nama = $request->atas_nama;
        $metode->status_pembayaran = $request->status_pembayaran;

        // Update kode_bayar
        if ($request->hasFile('kode_bayar_img')) {
            $path = $request->file('kode_bayar_img')->store('icons', 'public');
            $metode->kode_bayar = basename($path);
        } else if ($request->kode_bayar) {
            $metode->kode_bayar = $request->kode_bayar;
        }

        // Update icon
        if ($request->hasFile('icon')) {
            // Hapus file lama jika ada
            if ($metode->icon && Storage::exists('public/icons/' . $metode->icon)) {
                Storage::delete('public/icons/' . $metode->icon);
            }

            $fileName = $request->file('icon')->hashName();
            $request->file('icon')->storeAs('icons', $fileName, 'public');
            $metode->icon = $fileName;
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
        try {
            $metode = MetodePembayaranModel::findOrFail($id);

            DB::beginTransaction();

            // Hapus data di database
            $metode->delete();

            DB::commit();

            // Setelah berhasil commit, baru hapus file
            if ($metode->icon && Storage::disk('public')->exists('icons/' . $metode->icon)) {
                Storage::disk('public')->delete('icons/' . $metode->icon);
            }

            return response()->json([
                'success' => true,
                'message' => 'Metode pembayaran berhasil dihapus',
                'id' => $id
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Metode pembayaran tidak bisa dihapus karena masih digunakan pada data pesanan.'
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus metode pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}
