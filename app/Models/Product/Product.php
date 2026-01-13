<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;


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
        "state",
        "state_stock",
        "unidad_medida",
        "stock",
        "include_igv",

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

    // public function getProductImagenAttribute()
    // {
    //     $link = null;
    //     if($this->imagen){
    //         if(str_contains($this->imagen,"https://") || str_contains($this->imagen,"http://")){
    //             $link = $this->imagen;
    //         }else{
    //             $link =  env('APP_URL').'storage/'.$this->imagen;
    //         }
    //     }
    //     return $link;
    // }

    // public function scopeFilterAdvance($query,$search_product,$product_categorie_id,$state,$unidad_medida){
    //     if($search_product){
    //         $query->where(DB::raw("CONCAT(products.title,' ',products.sku)"),"like","%".$search_product."%");
    //     }
    //     if($product_categorie_id){
    //         $query->where("product_categorie_id",$product_categorie_id);
    //     }
    //     if($state){
    //         $query->where("state",$state);
    //     }
    //     if($unidad_medida){
    //         $query->where("unidad_medida",$unidad_medida);
    //     }
    //     return $query;
    // }
}
