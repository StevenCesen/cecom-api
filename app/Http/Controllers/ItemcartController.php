<?php

namespace App\Http\Controllers;

use App\Models\Itemcart;
use App\Models\Product;
use Illuminate\Http\Request;

class ItemcartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $create_itemcart=Itemcart::create($request->all());
        $producto=Product::where('id',$request->item_id)->first();
        $producto->quantity=$request->quantity;
        $producto->item_id=$create_itemcart->id;
        return response()->json([
            "status"=>200,
            "message"=>"Item agregado correctamente.",
            "data"=>$producto
        ],200);
        } catch (\Throwable $th) {
            return response()->json([
                "status"=>500,
                "message"=>"Item agregado correctamente.",
                "data"=>$th
            ],500);
        }
        

        
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
