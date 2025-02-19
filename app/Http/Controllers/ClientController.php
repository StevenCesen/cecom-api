<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contributor;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contributor $id)
    {
        return $id->find($id->id)->client()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $client_product=Client::create($request->all());
        return response()->json([
            "status"=>200,
            "message"=>"Cliente creado correctamente.",
            "data"=>$client_product
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $id)
    {
        return $id;
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
