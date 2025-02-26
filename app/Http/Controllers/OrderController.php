<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\Itemcart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contributor $id)
    {
        $orders=$id->find($id->id)
            ->order()
            ->when(request()->filled('status'),function($query){
                $query->where('status',request('status'));
            })
            ->when(request()->filled('client_name'),function($query){
                $query->where('client_name',request('client_name'));
            })
            ->when(request()->filled('date'),function($query){
                $query->where('create_date','<=',request('date'));
            })
            ->orderBy('create_date','DESC')
            ->paginate(10);
        
        foreach($orders as $order){
            $order->items=$order->find($order->id)->itemcart;
        }

        return $orders;
    }

    public function getNumOrderDay($contributor_id){
        $last_order=Order::where('contributor_id',$contributor_id)
            ->where('create_date','REGEXP',date('Y/m/d',time()-18000))
            ->orderBy('create_date','DESC')
            ->first();

        return ($last_order==null) ? 1 : ($last_order->order_number_day+1);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  Creamos la orden
        $data_order=[
            'floor'=>$request->client_piso,
            'table'=>$request->client_mesa,
            'create_date'=>date('Y/m/d H:i:s',time()-18000),
            'details'=>"",
            'notes'=>"",
            'status'=>"PENDIENTE",
            'client_name'=>$request->client_name,
            'client_identification'=>"",
            'order_number_day'=>$this->getNumOrderDay($request->contributor_id),
            'user_id'=>$request->user_id,
            'contributor_id'=>$request->contributor_id
        ];

        $create_order=Order::create($data_order);

        //  Insertamos los productos de la orden
        $items=json_decode($request->items);

        foreach($items as $item){
            $data_item=[
                'name'=>$item->name,
                'notes'=>$item->notes,
                'quantity'=>$item->quantity,
                'complements'=>"",
                'item_id'=>$item->id,
                'order_id'=>$create_order->id
            ];
            Itemcart::create($data_item);
        }

        //  Enviamos a imprimir
        $data = http_build_query(array(
            'data'=>json_encode([
                'floor'=>$request->client_piso,
                'table'=>$request->client_mesa,
                'create_date'=>date('Y/m/d H:i:s',time()-18000),
                'items'=>json_decode($request->items),
                'nro_order'=>$create_order->order_number_day,
                'client_name'=>$request->client_name,
                'order_number_day'=>$this->getNumOrderDay($request->contributor_id),
                'user'=>User::where('id',$request->user_id)->first()->name,
                'contributor'=>Contributor::where('id',$request->contributor_id)->first(),
                'context'=>"cocina"
            ])
        ));
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://srv479098.hstgr.cloud/connectvpn.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $resultado= curl_exec($ch);
        curl_close($ch);

        return response()->json([
            "status"=>200,
            "message"=>"Comanda generada correctamente.",
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
