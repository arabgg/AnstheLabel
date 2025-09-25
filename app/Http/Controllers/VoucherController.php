<?php

namespace App\Http\Controllers;

use App\Models\VoucherModel;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');
        $sort = $request->input('sort', 'terbaru');
        $voucher = VoucherModel::select(
            'voucher_id',
            'kode_voucher',
            'deskripsi',
            'tipe_diskon',
            'nilai_diskon',
            'min_transaksi',
            'usage_limit',
            'used',
            'tanggal_mulai',
            'tanggal_berakhir',
            'status_voucher',
            'created_at',
            'updated_at'
        )
            ->when(!empty($searchQuery), function ($q) use ($searchQuery) {
                $q->where('kode_voucher', 'like', "%{$searchQuery}%");
            })
            ->when($sort === 'terbaru', function ($q) {
                $q->orderBy('created_at', 'desc');
            })
            ->when($sort === 'terlama', function ($q) {
                $q->orderBy('created_at', 'asc');
            })
            ->paginate(10)
            ->appends($request->query());
        return view('admin.voucher.index', compact('voucher', 'searchQuery', 'sort'));
    }

    public function create()
    {
        $voucher = VoucherModel::all();
        return view('admin.voucher.create', compact('voucher'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_voucher'   => 'required|unique:m_voucher,kode_voucher',
            'deskripsi'      => 'nullable|string|max:255',
            'tipe_diskon'    => 'required|in:persen,nominal',
            'nilai_diskon'   => 'required|numeric|min:0',
            'min_transaksi'  => 'nullable|numeric|min:0',
            'usage_limit'    => 'nullable|integer|min:0',
            'tanggal_mulai'  => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status_voucher' => 'required|boolean',
        ]);

        $voucher = VoucherModel::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Voucher berhasil ditambahkan',
                'voucher' => $voucher
            ]);
        }

        return redirect()->route('voucher.index')->with('success', 'Voucher berhasil ditambahkan');
    }

    public function show($id)
    {
        $voucher = VoucherModel::findOrFail($id);

        if (request()->ajax()) {
            return view('admin.voucher.show', compact('voucher'));
        }

        return redirect()->route('voucher.index');
    }

    public function edit($id)
    {
        $voucher = VoucherModel::findOrFail($id);

        if (request()->ajax()) {
            return view('admin.voucher.edit', compact('voucher'))->render();
        }

        return view('admin.voucher.edit', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_voucher'   => 'required|unique:m_voucher,kode_voucher',
            'deskripsi'      => 'nullable|string|max:255',
            'tipe_diskon'    => 'required|in:persen,nominal',
            'nilai_diskon'   => 'required|numeric|min:0',
            'min_transaksi'  => 'nullable|numeric|min:0',
            'usage_limit'    => 'nullable|integer|min:0',
            'tanggal_mulai'  => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status_voucher' => 'required|boolean',
        ]);

        $voucher = VoucherModel::findOrFail($id);

        $voucher->kode_voucher = $validated['kode_voucher'];
        $voucher->deskripsi = $validated['deskripsi'];
        $voucher->tipe_diskon = $validated['tipe_diskon'];
        $voucher->nilai_diskon = $validated['nilai_diskon'];
        $voucher->min_transaksi = $validated['min_transaksi'];
        $voucher->usage_limit = $validated['usage_limit'];
        $voucher->tanggal_mulai = $validated['tanggal_mulai'];
        $voucher->tanggal_berakhir = $validated['tanggal_berakhir'];
        $voucher->status_voucher = $validated['status_voucher'];

        $voucher->save();

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diperbarui',
            'voucher' => $voucher
        ]);
    }

    public function destroy($id)
    {
        try {
            $voucher = VoucherModel::findOrFail($id);
            $voucher->delete();
            return response()->json([
                'success' => true,
                'message' => 'Voucher berhasil dihapus',
                'id' => $id
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak bisa dihapus karena masih digunakan pada produk.'
            ], 400);
        }
    }
}
