<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\Product\Brand;
use App\Models\Product\Categorie;


class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "id",
        "title",
        "sku",
        "imagen",
        "categorie_id",
        "price_general",
        "price_company",
        "description",
        "is_discount",
        "max_discount",
        "disponiblidad",
        'barcode',
        "state",
        "state_stock",
        "unidad_medida",
        "stock",
        "include_igv",
        "brand_id",

        "is_icbper",
        "is_ivap",
        "percentage_isc",
        "is_especial_nota",
    ];

    public function setCreatedAtAttribute($value)
    {
        date_default_timezone_set('America/Lima');
        $this->attributes["created_at"]= Carbon::now();
    }

    public function setUpdatedAtAttribute($value)
    {
        date_default_timezone_set("America/Lima");
        $this->attributes["updated_at"]= Carbon::now();
    }

    public function categorie() {
        return $this->belongsTo(Categorie::class,"categorie_id");
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class,"brand_id");
    }

    public static function generateSku($product)
    {
        $brand = $product->brand ?? null;
        if (!$brand && !empty($product->brand_id)) {
            $brand = Brand::find($product->brand_id);
        }

        $category = $product->categorie ?? null;
        if (!$category && !empty($product->categorie_id)) {
            $category = Categorie::find($product->categorie_id);
        }

        $brandPrefix = $brand
            ? strtoupper(substr($brand->name, 0, 3))
            : 'GEN';

        $categoryPrefix = $category
            ? strtoupper(substr($category->title, 0, 3))
            : 'VAR';

        $categoryCount = self::where('categorie_id', $product->categorie_id)->count();
        $nextNumber = $categoryCount + 1;

        $correlative = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return "{$brandPrefix}-{$categoryPrefix}-{$correlative}";
    }

    public function scopeFilterMultiple($query,$search, $categorie_id, $state, $unidad_medida, $brand_id){
        if($search){
            $query->where("title","like","%".$search."%");
        }
        if($categorie_id){
            $query->where("categorie_id",$categorie_id);
        }
        if($brand_id){
            $query->where("brand_id",$brand_id);
        }
        if($state){
            $query->where("state",$state);
        }
        if($unidad_medida){
            $query->where("unidad_medida",$unidad_medida);
        }
        return $query;
        //nos quedamos en 9:23
    }
}
