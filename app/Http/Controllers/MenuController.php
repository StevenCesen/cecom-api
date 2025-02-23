<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\Item;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contributor $id)
    {
        $menus=$id->find($id->id)
            ->menus()
            ->when(request()->filled('name'),function($query){
                $query->where('name','REGEXP',request('name'));
            })
            ->orderBy('status','ASC')
            ->paginate(10);
        
        foreach($menus as $menu){
            $menu->items=$menu->find($menu->id)->item;
            $menu->import=$menu->find($menu->id)
                ->item()
                ->sum(DB::raw('price_menu * quantity_menu'));
        }

        return $menus;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  Creamos el menú
        $create_menu=Menu::create([
            'name'=>$request->name,
            'status'=>$request->status,
            'create_date'=>date('Y/m/d H:i:s',time()-18000),
            'contributor_id'=>$request->contributor_id
        ]);

        //  Insertamos los productos
        $productos=json_decode($request->productos);

        foreach($productos as $producto){
            Item::create([
                'price_menu'=>$producto->price,
                'menu_id'=>$create_menu->id,
                'quantity_menu'=>$producto->quantity,
                'product_id'=>$producto->id
            ]);
        }

        return response()->json([
            "status"=>200,
            "message"=>"Menú creado correctamente.",
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $id)
    {
        $menu=DB::table('menus as m')
            ->leftJoin('items','items.menu_id','m.id')
            ->leftJoin('products','products.id','items.product_id')
            ->where('m.id',$id->id)
            ->get();
        
        return response()->json([
            "status"=>200,
            "data"=>[
                "name"=>$id->name,
                "status"=>$id->status,
                "items"=>$menu
            ],
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $id)
    {   
        //  Actualización de items
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $id)
    {
        $id->delete();

        return response()->json([
            "status"=>200,
            "message"=>"Menú eliminado.",
        ],200);
    }
}
