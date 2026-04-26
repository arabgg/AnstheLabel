<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiModel;
use App\Helpers\ApiResponse;

class TransactionController extends Controller
{
    public function show(Request $request, $kode_invoice)
    {
        $trx = TransaksiModel::where('kode_invoice', $kode_invoice)
            ->where('token', $request->token)
            ->first();

        if (!$trx) {
            return ApiResponse::error('Transaksi tidak ditemukan', 404);
        }

        return ApiResponse::success([
            'kode_invoice' => $trx->kode_invoice,
            'status' => $trx->status,
            'total' => $trx->total,
            'expired_at' => $trx->expired_at,
            'customer' => [
                'name' => $trx->customer_name,
                'email' => $trx->email
            ],
            'payment_proof' => $trx->payment_proof
        ]);
    }

    public function uploadPayment(Request $request, $kode_invoice)
    {
        $trx = TransaksiModel::where('kode_invoice', $kode_invoice)
            ->where('token', $request->token)
            ->first();

        if (!$trx) {
            return ApiResponse::error('Transaksi tidak ditemukan', 404);
        }

        if ($trx->status !== 'MENUNGGU_PEMBAYARAN') {
            return ApiResponse::error('Tidak bisa upload bukti');
        }

        if ($trx->payment_proof) {
            return ApiResponse::error('Bukti sudah diupload');
        }

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        $file = $request->file('bukti_pembayaran');
        $path = $file->store('payments', 'public');

        $trx->update([
            'payment_proof' => $path,
            'status' => 'MENUNGGU_VERIFIKASI'
        ]);

        return ApiResponse::success(null, 'Upload berhasil');
    }
}