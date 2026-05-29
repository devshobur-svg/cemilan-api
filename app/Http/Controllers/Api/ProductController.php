<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Ambil semua data produk real dari database
    public function index()
    {
        try {
            $products = Product::all();
            return response()->json([
                'success' => true,
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Tambah produk baru atau Update produk (Mendukung data gambar Base64 string)
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'nullable|numeric',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'stock' => 'required|numeric',
            'has_variants' => 'required|boolean',
            'variants' => 'nullable|array',
            'image' => 'nullable|string' // Menerima data Base64 string dari react galeri
        ]);

        try {
            $product = Product::updateOrCreate(
                ['id' => $request->id],
                [
                    'name' => $request->name,
                    'price' => $request->price,
                    'category' => $request->category,
                    'stock' => $request->stock,
                    'has_variants' => $request->has_variants,
                    'variants' => $request->variants ?? [],
                    // Gunakan image baru jika diupload, atau keep image lama, atau fallback placeholder jika kosong
                    'image' => $request->image ?? ($request->id ? Product::find($request->id)->image : 'https://images.unsplash.com/photo-1547058886-af77992d478c?w=150')
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Data produk berhasil diperbarui!',
                'data' => $product
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses manajemen stok produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus Produk dari database secara permanen
     * Endpoint: DELETE /api/products/{id}
     */
    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan!'
                ], 404);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jajanan berhasil dihapus dari sistem gudang!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}