<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function dependencies()
    {
        return ApiResponse::success([
            'expeditions' => DB::table('m_ekspedisi')->get(),
            'payment_methods' => DB::table('t_metode_pembayaran')->get()
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $total = 0;

            foreach ($request->items as $item) {
                $product = Product::where('id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$product || $product->stock < $item['qty']) {
                    throw new \Exception('Stok tidak cukup');
                }

                $total += $product->price * $item['qty'];

                $product->decrement('stock', $item['qty']);
            }

            $transaction = Transaction::create([
                'kode_invoice' => 'INV-' . strtoupper(Str::random(8)),
                'token' => Str::random(40),
                'total' => $total,
                'status' => 'MENUNGGU_PEMBAYARAN',
                'expired_at' => Carbon::now()->addHours(24),
                'customer_name' => $request->customer['name'],
                'email' => $request->customer['email'],
                'phone' => $request->customer['phone'],
                'address' => $request->customer['address'],
            ]);

            DB::commit();

            return ApiResponse::success([
                'kode_invoice' => $transaction->kode_invoice,
                'token' => $transaction->token,
                'total' => $total,
                'expired_at' => $transaction->expired_at
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage());
        }
    }
}