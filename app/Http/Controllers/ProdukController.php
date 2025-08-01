<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use App\Models\BahanModel;
use App\Models\ProdukModel;
use App\Models\WarnaProdukModel;
use App\Models\UkuranProdukModel;
use App\Models\FotoProdukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = ProdukModel::with(['kategori', 'bahan', 'fotoUtama'])->get();
        return view('produk.index', compact('produk'));
    }

    public function show($id)
    {
        $produk = ProdukModel::with(['kategori', 'bahan', 'foto', 'warnaProduk', 'ukuran', 'toko'])->findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    public function create()
    {
        $kategori = KategoriModel::all();
        $bahan = BahanModel::all();
        return view('produk.create', compact('kategori', 'bahan'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'deskripsi' => 'required',
            'kategori_id' => 'required',
            'bahan_id' => 'required',
            'warna' => 'array',
            'ukuran' => 'array',
            'foto' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $produk = ProdukModel::create($request->only(['kategori_id', 'bahan_id', 'nama_produk', 'deskripsi']));

        // Simpan warna produk
        if ($request->has('warna')) {
            foreach ($request->warna as $kode) {
                WarnaProdukModel::create([
                    'produk_id' => $produk->produk_id,
                    'kode_warna' => $kode,
                ]);
            }
        }

        // Simpan ukuran produk
        if ($request->has('ukuran')) {
            foreach ($request->ukuran as $ukuran) {
                UkuranProdukModel::create([
                    'produk_id' => $produk->produk_id,
                    'ukuran' => $ukuran,
                ]);
            }
        }

        // Simpan foto utama
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('produk', 'public');

            FotoProdukModel::create([
                'produk_id' => $produk->produk_id,
                'nama_file' => $path,
                'status_foto' => 1,
            ]);
        }

        return redirect('/produk')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $produk = ProdukModel::with(['warnaProduk', 'ukuran'])->findOrFail($id);
        $kategori = KategoriModel::all();
        $bahan = BahanModel::all();
        return view('produk.edit', compact('produk', 'kategori', 'bahan'));
    }

    public function update(Request $request, $id)
    {
        $produk = ProdukModel::findOrFail($id);
        $produk->update($request->only(['kategori_id', 'bahan_id', 'nama_produk', 'deskripsi']));

        // Update warna dan ukuran - sederhana: hapus lalu insert ulang
        WarnaProdukModel::where('produk_id', $id)->delete();
        UkuranProdukModel::where('produk_id', $id)->delete();

        if ($request->has('warna')) {
            foreach ($request->warna as $kode) {
                WarnaProdukModel::create([
                    'produk_id' => $id,
                    'kode_warna' => $kode,
                ]);
            }
        }

        if ($request->has('ukuran')) {
            foreach ($request->ukuran as $ukuran) {
                UkuranProdukModel::create([
                    'produk_id' => $id,
                    'ukuran' => $ukuran,
                ]);
            }
        }

        return redirect('/produk')->with('success', 'Produk berhasil diperbarui');
    }

    public function confirm($id)
    {
        $produk = ProdukModel::findOrFail($id);
        return view('produk.confirm', compact('produk'));
    }

    public function delete($id)
    {
        $produk = ProdukModel::findOrFail($id);

        // Hapus relasi terlebih dahulu
        WarnaProdukModel::where('produk_id', $id)->delete();
        UkuranProdukModel::where('produk_id', $id)->delete();
        FotoProdukModel::where('produk_id', $id)->delete();

        $produk->delete();

        return redirect('/produk')->with('success', 'Produk berhasil dihapus');
    }

    public function list()
    {
        $produk = ProdukModel::with(['kategori', 'bahan', 'fotoUtama'])->get();
        return response()->json($produk);
    }
}
