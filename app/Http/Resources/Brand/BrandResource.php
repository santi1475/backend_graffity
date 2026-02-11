<?php

namespace App\Http\Resources\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->resource->id,
            "name" => $this->resource->name,
            "image" => $this->resource->image
                ? env("APP_URL")."/storage/".$this->resource->image
                : null,
            "icon_name" => $this->resource->icon_name ?? 'Badge',

            "state" => $this->resource->state,
            "created_at" => $this->resource->created_at->format("Y-m-d h:i A"),
            "updated_at" => $this->resource->updated_at->format("Y-m-d h:i A"),
        ];
    }
}
