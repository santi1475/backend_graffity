<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductCollection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categorie_id = $request->input('categorie_id');
        $products = Product::orderBy("id", "desc") -> paginate(25);

        return response()->json([
            "total" => $products->total(),
            "paginate" => 25,
            "products" => ProductCollection::collection($products)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $is_product_title = Product::where("title", $request->title)->first();
        if ($is_product_title) {
            return response()->json([
                "code" => 405,
                "message" => "El título del producto ya existe."
            ]);
        }
        $is_product_sku = Product::where("sku", $request->sku)->first();
        if ($is_product_sku) {
            return response()->json([
                "code" => 405,
                "message" => "El SKU del producto ya existe."
            ]);
        }
        if($request->hasFile('image')){
            $path = Storage::putFile("products",$request->file('image'));
            $request->request->add(["imagen" => $path]);
        }
        $product = Product::create($request->all());
        return response()->json([
            "code" => 200,
            "message" => "Producto creado exitosamente.",
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            "product" => ProductResource::make($product)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $is_product_title = Product::where("id", "<>", $id)->where("title", $request->title)->first();
        if ($is_product_title) {
            return response()->json([
                "code" => 405,
                "message" => "El título del producto ya existe."
            ]);
        }
        $is_product_sku = Product::where("id", "<>", $id)->where("sku", $request->sku)->first();
        if ($is_product_sku) {
            return response()->json([
                "code" => 405,
                "message" => "El SKU del producto ya existe."
            ]);
        }
        $product = Product::findOrFail($id);

        if($request->hasFile('image')){
            if($product->imagen){
                Storage::delete($product->imagen);
            }
            $path = Storage::putFile("products",$request->file('image'));
            $request->request->add(["imagen" => $path]);
        }
        $product->update($request->all());
        return response()->json([
            "code" => 200,
            "message" => "Producto actualizado exitosamente.",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        if($product->imagen){
            Storage::delete($product->imagen);
        }
        $product->delete();
        return response()->json([
            "code" => 200,
            "message" => "Producto eliminado exitosamente.",
        ]);
    }
}
