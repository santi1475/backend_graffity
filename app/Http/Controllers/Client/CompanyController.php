<?php

namespace App\Http\Controllers\Client;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\CompanyResource;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::first();

        return response()->json([
            "code" => 200,
            "company" => $company ? CompanyResource::make($company) : null,
        ]);
    }

    public function store(Request $request)
    {
        $company = Company::first();

        if($company){
            $company->update($request->all());
        }else{
            Company::create($request->all());
        }
        return response()->json([
            "code" => 200,
            "message" => "Empresa actualizada correctamente.",
            "company" => $company
        ]);
    }
}
