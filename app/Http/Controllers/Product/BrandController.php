<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Models\Product\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Product\BrandResource;
use App\Http\Resources\Product\BrandCollection;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get("search");
        $brands = Brand::where("name", "like", "%".$search."%")
            ->orderBy("id", "desc")
            ->paginate(5);

        return response()->json([
            "total" => $brands->total(),
            "paginate" => 5,
            "brands" => [
                "data" => BrandResource::collection($brands)
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $is_exists = Brand::where("name", $request->name)->first();
        if ($is_exists) {
            return response()->json([
                "code" => 400,
                "message" => "La marca ya existe."
            ], 400);
        }

        if($request->hasFile("image")){
            $path = Storage::putFile("brands",$request->file("image"));
            $request->request->add(["image" => $path]);
        }

        $brand = Brand::create($request->all());

        return response()->json([
            "code" => 200,
            "message" => "Marca creada correctamente.",
            "brand" => BrandResource::make($brand)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json([
            "brand" => BrandResource::make($brand)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $is_exists = Brand::where("id","<>", $id)->where("name", $request->name)->first();
        if ($is_exists) {
            return response()->json([
                "code" => 400,
                "message" => "La marca ya existe."
            ], 400);
        }

        $brand = Brand::findOrFail($id);

        if($request->hasFile("image")){
            if($brand->image){
                Storage::delete($brand->image);
            }
            $path = Storage::putFile("brands",$request->file("image"));
            $request->request->add(["image" => $path]);
        }

        $brand->update($request->all());

        return response()->json([
            "code" => 200,
            "message" => "Marca editada correctamente.",
            "brand" => BrandResource::make($brand)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);

        if($brand->image){
            Storage::delete($brand->image);
        }

        $brand->delete();

        return response()->json([
            "code" => 200,
            "message" => "Marca eliminada correctamente.",
        ], 200);
    }
}
