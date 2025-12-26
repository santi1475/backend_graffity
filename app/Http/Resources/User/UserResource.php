<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "email" => $this->resource->email,
            "surname" => $this->resource->surname,
            "full_name" => $this->resource->name . ' ' . $this->resource->surname,
            "role_id" => $this->resource->role_id,
            "role" =>[
                "id"=> $this->resource->role->id,
                "name"=> $this->resource->role->name,
            ],
            "phone" => $this->resource->phone,
            "avatar" => $this->resource->avatar ? env("APP_URL") . "storage/" . $this->resource->avatar : NULL,
            "type_document" => $this->resource->type_document,
            "n_document" => $this->resource->n_document,
            "gender" => $this->resource->gender,
            "state" => $this->resource->state,
            "created_at" => $this->resource->created_at->format("Y-m-d h:i A"),
        ];
    }
}
