<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use App\Models\BahanModel;
use App\Models\ProdukModel;
use App\Models\WarnaProdukModel;
use App\Models\WarnaModel;
use App\Models\UkuranModel;
use App\Models\UkuranProdukModel;
use App\Models\FotoProdukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = ProdukModel::whereHas('fotoUtama') // hanya produk yang punya foto utama
            ->with(['kategori', 'bahan', 'fotoUtama'])
            ->get();

        return view('produk.index', compact('produk'));
    }

    public function show($id)
    {
        $produk = ProdukModel::with(['kategori', 'bahan', 'foto', 'warna', 'ukuran', 'toko'])->findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    public function create()
    {
        $kategori = KategoriModel::all();
        $bahan = BahanModel::all();
        $ukuran = UkuranModel::all();
        $warna = WarnaModel::all();
        return view('produk.create', compact('kategori', 'bahan', 'ukuran', 'warna'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto_utama' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_sekunder.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_id' => 'required|integer',
            'bahan_id' => 'required|integer',
        ], [
            'foto_utama.required' => 'Foto utama wajib diunggah.',
            'foto_utama.image' => 'File harus berupa gambar.',
            'foto_utama.mimes' => 'Format gambar hanya boleh jpeg, png, atau jpg.',
            'foto_utama.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        // Simpan produk
        $produk = ProdukModel::create([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'kategori_id' => $request->kategori_id,
            'bahan_id' => $request->bahan_id,
        ]);

        // Foto utama
        if ($request->hasFile('foto_utama')) {
            $path = $request->file('foto_utama')->store('foto_produk', 'public');
            FotoProdukModel::create([
                'produk_id' => $produk->produk_id,
                'path' => $path,
                'status_foto' => 1 // 1 = foto utama
            ]);
        }

        // Foto sekunder
        if ($request->hasFile('foto_sekunder')) {
            foreach ($request->file('foto_sekunder') as $foto) {
                $path = $foto->store('foto_produk', 'public');
                FotoProdukModel::create([
                    'produk_id' => $produk->produk_id,
                    'path' => $path,
                    'status_foto' => 0 // 0 = foto biasa
                ]);
            }
        }

        // Ukuran
        if ($request->has('ukuran')) {
            foreach ($request->ukuran as $ukuran) {
                UkuranProdukModel::create([
                    'produk_id' => $produk->produk_id,
                    'ukuran' => $ukuran,
                ]);
            }
        }
        
        // Warna
        if ($request->has('warna')) {
            foreach ($request->warna as $kode) {
                WarnaProdukModel::create([
                    'produk_id' => $produk->produk_id,
                    'kode_warna' => $kode,
                ]);
            }
        }



        return redirect()->route('produk.create')->with('success', 'Produk berhasil disimpan!');
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

    public function destroy($id)
    {
        try {
            // Cari data produk
            $produk = ProdukModel::findOrFail($id);

            // Hapus relasi dulu
            $produk->toko()->delete();          // t_toko_produk
            $produk->warnaProduk()->delete();   // t_warna_produk
            $produk->ukuran()->delete();        // t_ukuran_produk
            $produk->foto()->delete();          // t_foto_produk

            // Setelah semua relasi dihapus, baru hapus produk utamanya
            $produk->delete();

            return redirect('/produk')->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect('/produk')->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
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
