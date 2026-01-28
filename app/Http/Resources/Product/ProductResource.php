<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->resource->id,
            "title"=>$this->resource->title,
            "sku"=>$this->resource->sku,
            "imagen"=>env("APP_URL")."/storage/".$this->resource->imagen,
            "categorie_id"=>$this->resource->categorie_id,
            "categorie"=> [
                "id"=>$this->resource->categorie->id,
                "title"=>$this->resource->categorie->title,
            ],
            "price_general"=>$this->resource->price_general,
            "price_company"=>$this->resource->price_company,
            "description"=>$this->resource->description,
            "is_discount"=>$this->resource->is_discount,
            "max_discount"=>$this->resource->max_discount,
            "disponiblidad"=>$this->resource->disponiblidad,
            "state"=>$this->resource->state,
            "state_stock"=>$this->resource->state_stock,
            "unidad_medida"=>$this->resource->unidad_medida,
            "stock"=>$this->resource->stock,
            "include_igv"=>$this->resource->include_igv,
            "is_icbper"=>$this->resource->is_icbper,
            "is_ivap"=>$this->resource->is_ivap,
            "percentage_isc"=>$this->resource->percentage_isc,
            "is_especial_nota"=>$this->resource->is_especial_nota,
            "created_at"=>$this->resource->created_at->format("Y-m-d h:i A"),
        ];
    }
}
