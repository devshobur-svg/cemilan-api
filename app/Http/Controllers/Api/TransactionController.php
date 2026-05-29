<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        try {
            $transactions = Transaction::with('items')->latest()->get();
            return response()->json([
                'success' => true,
                'data' => $transactions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|string',
            'total_price' => 'required|numeric',
            'cash_received' => 'required|numeric',
            'cash_change' => 'required|numeric',
            'items' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan Ringkasan Transaksi
            $transaction = Transaction::create([
                'invoice_no' => $request->invoice_no,
                'total_price' => $request->total_price,
                'cash_received' => $request->cash_received,
                'cash_change' => $request->cash_change,
            ]);

            // 2. Simpan Detail Item Jajanan & POTONG STOK UTAMA
            foreach ($request->items as $item) {
                // Cari produk asli berdasarkan nama di database
                $product = Product::where('name', $item['name'])->first();

                if ($product) {
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok untuk jajanan '{$product->name}' tidak cukup! Sisa: {$product->stock}");
                    }
                    // Potong stok produk asli
                    $product->decrement('stock', $item['quantity']);
                }

                $transaction->items()->create([
                    'product_name' => $item['name'] ?? 'Jajanan',
                    'variant_name' => $item['selectedVariant'] ?? '-',
                    'price' => $item['price'] ?? 0,
                    'quantity' => $item['quantity'] ?? 1,
                    'subtotal' => ($item['price'] ?? 0) * ($item['quantity'] ?? 1),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi UMKM berhasil dicatat & stok terpotong!',
                'data' => $transaction->load('items')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}