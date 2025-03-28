<?php

namespace App\Http\Controllers;

use App\Models\TicketInteraction;
use Illuminate\Http\Request;

class TicketInteractionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data=[
            "date_create"=>date('Y/m/d H:i:s',time()-18000),
            "ticket_id"=>$request->ticket_id,
            "detail"=>$request->detail,
            "media"=>$request->media
        ];

        $create=TicketInteraction::create($data);

        return response()->json([
            "status"=>200,
            "message"=>"ITEM agregado."
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,TicketInteraction $id)
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
    public function destroy(TicketInteraction $id)
    {   
        $delete=$id->delete();

        return response()->json([
            "status"=>200,
            "message"=>"ITEM eliminado"
        ],200);
    }
}
