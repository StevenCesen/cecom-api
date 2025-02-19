<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\Establishment;
use Illuminate\Http\Request;

class EstablishmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contributor $id)
    {
        return $id->find($id->id)->establishment;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $exits=Establishment::where('user_id',$request->user_id)
            ->first();

        if($exits==null){
            //  Si no existe lo creamos
            $create_estab=Establishment::create($request->all());
        }else{
            $update_estab=Establishment::where('user_id',$request->user_id)
                ->update($request->all());
        }

        return response()->json([
            "status"=>200,
            "message"=>"Establecimiento actualizado correctamente."
        ],200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
