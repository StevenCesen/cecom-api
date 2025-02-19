<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Contributor $id)
    {   
        if(($id->user_limit==-1) | $id->find($id->id)->user()->count()<$id->user_limit){

            $create_user=User::create($request->all());
    
            return response()->json([
                "status"=>200,
                "message"=>"Usuario creado correctamente.",
                "data"=>$create_user
            ],200);

        }else{

            return response()->json([
                "status"=>400,
                "message"=>"Límite de usuarios superado. Incrementa tu plan."
            ],400);
        
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $id)
    {
        $password=Hash::make($request->password);

        $id->update([
            "password"=>$password
        ]);

        return response()->json([
            "status"=>200,
            "message"=>"Contraseña actualizada correctamente."
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
