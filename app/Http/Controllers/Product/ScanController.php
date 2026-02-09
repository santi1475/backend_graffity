<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\ProductScanned;
use App\Models\Product\Product;

class ScanController extends Controller
{
    public function processScan(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
            'channel_uuid' => 'required|string',
        ]);

        $barcode = $request->barcode;
        $channelId = $request->channel_uuid;

        $product = Product::where('sku', $barcode)->first();

        ProductScanned::dispatch($barcode, $channelId, $product);

        return response()->json([
            'message' => 'Código enviado correctamente',
            'product_found' => (bool) $product
        ]);
    }
}
