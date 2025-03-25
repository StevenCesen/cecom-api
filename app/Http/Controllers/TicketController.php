<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contributor;
use App\Models\Ticket;
use App\Models\TicketComplement;
use App\Models\TicketCost;
use App\Models\TicketInteraction;
use App\Models\TicketPay;
use Illuminate\Http\Request;

class TicketController extends Controller
{   
    /**
     * Display a listing of the resource.
     */
    public function index(Contributor $id)
    {
        return $id->find($id->id)
            ->ticket()
            ->when(request()->filled('status'),function($query){
                $query->where('status',request('status'));
            })
            ->when(request()->filled('stars'),function($query){
                $query->where('stars',intval(request('stars')));
            })
            ->when(request()->filled('user_id'),function($query){
                $query->where('user_id',intval(request('user_id')));
            })
            ->when(request()->filled('client_id'),function($query){
                $query->where('client_id',intval(request('client_id')));
            })
            ->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $client=Client::where('identification',$request->client_identification)->first();
        
        if($client==null){
            $client=Client::create([
                'identification'=>$request->client_identification,
                'name'=>$request->client_name,
                'direction'=>$request->client_direction,
                'phone'=>$request->client_phone,
                'email'=>$request->client_email,
                'contributor_id'=>$request->contributor_id
            ]);
        }

        $data=[
            'title'=>$request->name,
            'date_create'=>date('Y/m/d H:i:s',time()-18000),
            'date_finish'=>$request->date_finish,
            'last_interaction'=>$request->description,
            'status'=>'EN PROCESO',
            'client_id'=>$client->id,
            'user_id'=>$request->user_id,
            'contributor_id'=>$request->contributor_id
        ];

        $create_ticket=Ticket::create($data);

        //  Creamos la interacción con el ticket
        $create_interaction=TicketInteraction::create([
            'date_create'=>date('Y/m/d H:i:s',time()-18000),
            'detail'=>$request->description,
            'media'=>"",
            'ticket_id'=>$create_ticket->id
        ]);

        //  Si el ticket tiene complementos (que pueden ser en el caso de una reparación de computadora: cargador, estuche, etc.) los creamos
        if(request()->filled('complements')){
            $complements=json_decode($request->complements);

            foreach($complements as $complement){
                $create_complement=TicketComplement::create([
                    'date_create'=>date('Y/m/d H:i:s',time()-18000),
                    'text'=>$complement->text,
                    'quantity'=>$complement->quantity,
                    'media'=>json_encode($complement->media),
                    'ticket_id'=>$create_ticket->id
                ]);
            }
        }

        //  Creamos los costos del ticket: Mano de obra,...
        $costs=json_decode($request->products);

        foreach($costs as $cost){
            $create_cost=TicketCost::create([
                'date_create'=>date('Y/m/d H:i:s',time()-18000),
                'quantity'=>$cost->quantity,
                'product_id'=>$cost->id,
                'ticket_id'=>$create_ticket->id,
            ]);
        }

        //  Si hay abono lo guardo
        if(floatval($request->value_pay)>0){
            $create_pay=TicketPay::create([
                'date_create'=>date('Y/m/d H:i:s',time()-18000),
                'pay_value'=>$request->value_pay,
                'pay_way'=>$request->pay_way,
                'pay_type'=>$request->pay_type,
                'ticket_id'=>$create_ticket->id
            ]);
        }

        return response()->json([
            "status"=>200,
            "message"=>"Ticket generado correctamente.",
            "data"=>$create_ticket
        ],200);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Ticket $id)
    {
        return response()->json([
            "status"=>200,
            "data"=>$id
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $id)
    {
        $update_ticket=$id->update($request->all());

        return response()->json([
            "status"=>200,
            "data"=>$update_ticket
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $id)
    {
        $delete_ticket=$id->delete();

        return response()->json([
            "status"=>200,
            "data"=>$delete_ticket
        ],200);
    }
}
