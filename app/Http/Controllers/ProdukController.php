<?php

namespace App\Http\Controllers;

use App\Models\DetailProdukModel;
use App\Models\KategoriModel;
use App\Models\ProdukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function index()
    {
        // $activeMenu = 'kegiatanjti';
        // $breadcrumb = (object) [
        //     'title' => 'Data Kegiatan JTI',
        //     'list' => ['Home', 'Kegiatan JTI']
        // ];

        $produk = ProdukModel::all();
        $kategori = KategoriModel::all();
        $detail = DetailProdukModel::all();
    
        return view('admin.index', [
            'produk' => $produk,
            'kategori' => $kategori,
            'detail' => $detail
        ]);
    }

    public function create_ajax()
    {   
        $produk = ProdukModel::get();
        
        $kategori = KategoriModel::select('kategori_id', 'nama_kategori')->get();
        $detail = DetailProdukModel::select('detail_produk_id', 'warna', 'ukuran')->get();

        return view('admin.create_ajax')->with([
            'produk' => $produk,
            'kategori' => $kategori,
            'detail' => $detail
        ]);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => 'required|integer|exists:m_kategori,kategori_id',
                'detail_produk_id' => 'required|integer|exists:m_detail_produk,detail_produk_id',
                'nama_produk' => 'required|string|max:200',
                'harga' => 'required|string|max:100',
                'foto_produk' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'deskripsi' => 'required|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            try {
                $data = [
                    'kategori_id' => $request->kategori_id,
                    'detail_produk_id' => $request->detail_produk_id,
                    'nama_produk' => $request->nama_produk,
                    'harga' => $request->harga,
                    'deskripsi' => $request->deskripsi,
                ];

                // Proses simpan file ke public/storage/foto_produk
                if ($request->hasFile('foto_produk')) {
                    $foto = $request->file('foto_produk');
                    $filename = time() . '_' . $foto->getClientOriginalName();
                    $path = public_path('storage/foto_produk');

                    // Cek dan buat folder jika belum ada
                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }

                    // Simpan file ke public/storage/foto_produk
                    $foto->move($path, $filename);

                    // Simpan path file ke database
                    $data['foto_produk'] = 'storage/foto_produk/' . $filename;
                }

                ProdukModel::create($data);

                return response()->json([
                    'status' => true,
                    'message' => 'Produk Berhasil Ditambahkan'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data',
                    'error' => $e->getMessage()
                ]);
            }
        }

        return redirect('/home');
    }

    public function edit_ajax(string $id)
    {   
        $produk = ProdukModel::find($id);
        $kategori = KategoriModel::select('kategori_id', 'nama_kategori')->get();
        $detail = DetailProdukModel::select('detail_produk_id', 'warna', 'ukuran')->get();

        return view('admin.edit_ajax', [
            'produk' => $produk,
            'kategori' => $kategori,
            'detail' => $detail
        ]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => 'required|integer|exists:m_kategori,kategori_id',
                'detail_produk_id' => 'required|integer|exists:m_detail_produk,detail_produk_id',
                'nama_produk' => 'required|string|max:200',
                'harga' => 'required|string|max:100',
                'foto_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'deskripsi' => 'required|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $produk = ProdukModel::find($id);
            if (!$produk) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan.'
                ]);
            }

            try {
                $data = [
                    'kategori_id' => $request->kategori_id,
                    'detail_produk_id' => $request->detail_produk_id,
                    'nama_produk' => $request->nama_produk,
                    'harga' => $request->harga,
                    'deskripsi' => $request->deskripsi,
                ];

                // Proses update foto jika ada file baru dikirim
                if ($request->hasFile('foto_produk')) {
                    // Hapus foto lama jika ada
                    if ($produk->foto_produk && file_exists(public_path($produk->foto_produk))) {
                        unlink(public_path($produk->foto_produk));
                    }

                    $foto = $request->file('foto_produk');
                    $filename = time() . '_' . $foto->getClientOriginalName();
                    $path = public_path('storage/foto_produk');

                    // Buat folder jika belum ada
                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }

                    // Pindahkan file baru
                    $foto->move($path, $filename);

                    // Set path foto baru
                    $data['foto_produk'] = 'storage/foto_produk/' . $filename;
                }

                // Update data
                $produk->update($data);

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate.'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat update.',
                    'error' => $e->getMessage()
                ]);
            }
        }

        return redirect('/home');
    }

    public function confirm_ajax(string $id)
    {   
        $produk = ProdukModel::find($id);

        return view('admin.confirm_ajax', [
            'produk' => $produk
        ]);
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $produk = ProdukModel::find($id);

            if ($produk) {
                // Hapus semua data terkait
                $produk->kategori()->delete();
                $produk->detail()->delete();
                $produk->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/home');
    }
}
