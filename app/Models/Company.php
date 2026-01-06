<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        "razon_social",
        "razon_social_comercial",
        "phone",
        "email",
        "n_document",
        "birth_date",
        "ubigeo_region",
        "ubigeo_provincia",
        "ubigeo_distrito",
        "region",
        "provincia",
        "distrito",
        "address",
        "urbanizacion",
        "cod_local",
    ];
}
