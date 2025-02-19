<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contributor $id)
    {
        return $id->find($id->id)->type;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $create_type=Type::create($request->all());
        return response()->json([
            "status"=>200,
            "message"=>"Categoría creada correctamente.",
            "data"=>$create_type
        ],200);

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
