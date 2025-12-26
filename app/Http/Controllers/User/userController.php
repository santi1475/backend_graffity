<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;
use Illuminate\Container\Attributes\Storage as AttributesStorage;
use Symfony\Component\HttpKernel\HttpCache\Store;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get("search");

        $query = User::query();

        if($search) {
            $query->where('name', 'like', "%".$search."%")
                  ->orWhere('surname', 'like', "%".$search."%")
                  ->orWhere('email', 'like', "%".$search."%")
                  ->orWhere('phone', 'like', "%".$search."%")
                  ->orWhere('n_document', 'like', "%".$search."%");
        } //Se emplea de esta manera al tener una base de datos PostgreSQL

        $users = $query->orderBy("id","desc")->paginate(25);

        return response()->json([
            "total" => $users->total(),
            "paginate" => 25,
            "users" => UserCollection::make($users),

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $is_exists = User::where("email", $request->email)->first();

        if($is_exists) {
            return response()->json([
                "code" => 405,
                "message" => "El correo $is_exists->email ya se encuentra registrado"
            ]);
        }

        if($request->hasFile("avatar")) {
            $path = Storage::putFile("users",$request->file("avatar"));
            $request->request->add(["avatar" => $path]);
        }

        if($request->password){
            $request->request->add(["password" => bcrypt($request->password)]);
        }

        $user = User::create($request->all());
        $role = Role::find($request->role_id);
        $user->assignRole($role);

        return response()->json([
            "code" => 200,
            "message" => "Usuario creado correctamente",
            "user" => UserResource::make($user),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $is_exists = User::where("id","<>",$id)->where("email", $request->email)->first();

        if($is_exists) {
            return response()->json([
                "code" => 405,
                "message" => "El correo $is_exists->email ya se encuentra registrado"
            ]);
        }

        $user = User::findOrFail($id);

        if($request->hasFile("avatar")) {
            if($user -> avatar){
                Storage::delete($user -> avatar);
            }
            $path = Storage::putFile("users",$request->file("avatar"));
            $request->request->add(["avatar" => $path]);
        }

        if($request->password){
            $request->request->add(["password" => bcrypt($request->password)]);
        }

        if($user->role_id != $request->role_id){
            $role_current = Role::find($user->role_id);
            $user->removeRole($role_current);

            $role_new = Role::find($request->role_id);
            $user->assignRole($role_new);
        }


        $user->update($request->all());

        return response()->json([
            "code" => 200,
            "message" => "Usuario actualizado correctamente",
            "user" => UserResource::make($user),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if($user -> avatar){
            Storage::delete($user -> avatar);
        }

        $user->delete();
        return response()->json([
            "code" => 200,
            "message" => "Usuario eliminado correctamente",
        ]);
    }
}
