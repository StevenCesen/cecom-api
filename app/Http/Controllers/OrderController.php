<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\Establishment;
use App\Models\Item;
use App\Models\Itemcart;
use App\Models\Order;
use App\Models\Product;
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
            ->when(request()->filled('user_id'),function($query){
                $query->where('user_id','<=',request('user_id'));
            })
            ->whereIn('status',['PENDIENTE','EN MESA'])
            ->orderBy('create_date','DESC')
            ->paginate(1000);
        
        foreach($orders as $order){
            $order->items=$order->find($order->id)->itemcart;
        }

        return $orders;
    }

    public function indexMesero(Contributor $id)
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
            ->when(request()->filled('user_id'),function($query){
                $query->where('user_id','=',request('user_id'));
            })
            ->whereIn('status',['PENDIENTE'])
            ->orderBy('create_date','DESC')
            ->paginate(1000);
        
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
                'context'=>"comanda"
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
            "resultado"=>$resultado
        ],200);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Order $id)
    {   
        $id->details=$id->find($id->id)->itemcart()->whereNull('status_pay')->get();

        foreach($id->details as $item){
            // $it=Item::where($item->item_id)->first();
            $product=Product::where('id',$item->item_id)->first();
            $item->name=$product->name;
            $item->description=$product->description;
            $item->tax=$product->tax;
            $item->price=$product->price;
        }

        $id->contributor=$id->find($id->id)->contributor;
        $id->contributor->cert=($id->contributor->certificate!=null) ? base64_encode(file_get_contents('certs/'.$id->contributor->signature_path)) : "";
        $id->establishment=Establishment::where('contributor_id',$id->contributor_id)->first();

        return $id;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $id)
    {
        $update=$id->update([
            "status"=>"EN MESA"
        ]);

        return $update;
    }

    public function addItems(Request $request)
    {   
        //  Insertamos los productos de la orden
        $items=json_decode($request->items);

        foreach($items as $item){
            $data_item=[
                'name'=>$item->name,
                'notes'=>$item->notes,
                'quantity'=>$item->quantity,
                'complements'=>"",
                'item_id'=>$item->id,
                'order_id'=>$request->order_id
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
                'nro_order'=>$request->order_number_day,
                'client_name'=>$request->client_name." - COMANDA ACTUALIZADA",
                'order_number_day'=>$request->order_number_day,
                'user'=>User::where('id',$request->user_id)->first()->name,
                'contributor'=>Contributor::where('id',$request->contributor_id)->first(),
                'context'=>"comanda"
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
            "message"=>"Comanda actualizada."
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
