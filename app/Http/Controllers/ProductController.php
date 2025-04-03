<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contributor $id)
    {
        return $id->find($id->id)
                    ->product()
                    ->when(request()->filled('name'),function($query){
                        $query->where('name','REGEXP',request('name'));
                    })
                    ->when(request()->filled('type'),function($query){
                        $query->where('type_id',intval(request('type')));
                    })
                    ->paginate(1000);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $create_product=Product::create($request->all());
        return response()->json([
            "status"=>200,
            "message"=>"Producto creado correctamente.",
            "data"=>$create_product
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $id)
    {   
        return $id;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $id)
    {
        $id->update($request->all());
        
        return response()->json([
            "status"=>200,
            "message"=>"Producto creado correctamente."
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
