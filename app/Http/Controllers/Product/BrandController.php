<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Models\Product\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Brand\BrandCollection;

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
            ->paginate(25);

        return response()->json([
            "total" => $brands->total(),
            "paginate" => 25,
            "brands" => $brands->map(function ($brand) {
                return [
                    "id" => $brand->id,
                    "name" => $brand->name,
                    "image" => $brand->image ? env("APP_URL")."/storage/".$brand->image : null,
                    "state" => $brand->state,
                    "created_at" => $brand->created_at->format("Y/m/d H:i A"),
                ];
            }),
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

       $data = $request->all();

        if($request->hasFile("image")){
            $path = Storage::putFile("brands", $request->file("image"));
            $data["image"] = $path;
        }

        if(!$request->has('icon_name')){
             $data['icon_name'] = 'Badge'; // Default
        }

        $brand = Brand::create($data);

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
