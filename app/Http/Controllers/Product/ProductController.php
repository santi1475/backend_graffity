<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Product\Categorie;
use App\Models\Product\Brand;
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
        $search = $request->get("search");
        $categorie_id = $request->get('categorie_id');
        $brand_id = $request->get('brand_id');
        $state = $request->get('state');
        $unidad_medida = $request->get('unidad_medida');

        $products = Product::with(["categorie", "brand"])
            ->FilterMultiple($search, $categorie_id, $state, $unidad_medida, $brand_id)
            ->orderBy("id", "desc")
            ->paginate(25);

        return response()->json([
            "total" => $products->total(),
            "paginate" => 25,
            "products" => [
                "data" => ProductResource::collection($products)
            ]
        ], 200);
    }
    public function config()
    {
        $categories = Categorie::where("state", 1)->get();
        $brands = Brand::where("state", 1)->get();
        return response()->json([
            "categories" => $categories->map(function($categorie){
                return [
                    "id" => $categorie->id,
                    "title" => $categorie->title    ,
                ];
            }),
            "brands" => $brands->map(function($brand){
                return [
                    "id" => $brand->id,
                    "name" => $brand->name,
                ];
            })
        ]);
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
        $sku = $request->sku;
        if (!$sku) {
            $draftProduct = new Product($request->all());
            $sku = Product::generateSku($draftProduct);
        }
        $is_product_sku = Product::where("sku", $sku)->first();
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
        $request->request->add(["sku" => $sku]);
        $product = Product::create($request->all());
        return response()->json([
            "code" => 200,
            "message" => "Producto creado exitosamente.",
            "product" => ProductResource::make($product)
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
        $product = Product::findOrFail($id);

        $sku = $request->sku;
        $shouldRegenerateSku = !$sku && (
            ($request->filled("brand_id") && $request->brand_id != $product->brand_id) ||
            ($request->filled("categorie_id") && $request->categorie_id != $product->categorie_id) ||
            !$product->sku
        );
        if ($shouldRegenerateSku) {
            $draftProduct = new Product(array_merge($product->toArray(), $request->all()));
            $sku = Product::generateSku($draftProduct);
        }
        if ($sku) {
            $is_product_sku = Product::where("id", "<>", $id)->where("sku", $sku)->first();
            if ($is_product_sku) {
                return response()->json([
                    "code" => 405,
                    "message" => "El SKU del producto ya existe."
                ]);
            }
            $request->request->add(["sku" => $sku]);
        }

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
            "product" => ProductResource::make($product)
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
