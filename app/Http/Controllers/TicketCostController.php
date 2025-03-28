<?php

namespace App\Http\Controllers;

use App\Models\TicketCost;
use Illuminate\Http\Request;

class TicketCostController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data=[
            "date_create"=>date('Y/m/d H:i:s',time()-18000),
            "ticket_id"=>$request->ticket_id,
            "quantity"=>$request->quantity,
            "product_id"=>$request->product_id
        ];

        $create=TicketCost::create($data);
        
        return response()->json([
            "status"=>200,
            "message"=>"ITEM agregado."
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,TicketCost $id)
    {
        $update=$id->update($request->all());

        return response()->json([
            "status"=>200,
            "message"=>"ITEM actualizado"
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketCost $id)
    {   
        $delete=$id->delete();

        return response()->json([
            "status"=>200,
            "message"=>"ITEM eliminado"
        ],200);
    }
}
