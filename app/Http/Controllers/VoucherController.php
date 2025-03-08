<?php

namespace App\Http\Controllers;

use App\Mail\SendMailable;
use App\Models\Client;
use App\Models\Contributor;
use App\Models\Establishment;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contributor $id)
    {
        $vouchers=$id->find($id->id)
            ->voucher()
            ->orderBy('create_date','DESC')
            ->paginate(10);
        
        foreach($vouchers as $voucher){
            $voucher->client=Client::where('id',$voucher->client_id)->first();
        }

        return $vouchers;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data=[
            'sequential'=>$request->sequential,
            'access_key'=>$request->access_key,
            'doc_type'=>$request->doc_type,
            'issue_date'=>$request->issue_date,
            'create_date'=>$request->create_date,
            'subtotal_amount'=>$request->subtotal_amount,
            'tax_value'=>$request->tax_value,
            'total_amount'=>$request->total_amount,
            'ride_path'=>$request->ride_path,
            'xml_path'=>$request->xml_path,
            'status'=>$request->status,
            'contributor_id'=>$request->contributor_id,
            'client_id'=>""
        ];

        $client=Client::where('identification',$request->client_identification)->first();
        
        if($client==null){

            $create_user=Client::create([
                'identification'=>$request->client_identification,
                'name'=>$request->client_name,
                'direction'=>$request->client_direction,
                'phone'=>$request->client_phone,
                'email'=>$request->client_email,
                'contributor_id'=>$request->contributor_id
            ]);

            $data['client_id']=$create_user->id;

        }else{
            $data['client_id']=$client->id;
        }

        //  Actualizamos el secuencial de la factura
        if($request->doc_type=='01'){
            Establishment::where('contributor_id',$request->contributor_id)
                ->where('nro_estab',$request->nro_estab)
                ->update([
                    "nro_invoices"=>intval($request->sequential)
                ]);
        }else if($request->doc_type=='03'){
            Establishment::where('contributor_id',$request->contributor_id)
                ->where('nro_estab',$request->nro_estab)
                ->update([
                    "nro_liquidations"=>intval($request->sequential)
                ]);
        }else if($request->doc_type=='04'){
            Establishment::where('contributor_id',$request->contributor_id)
                ->where('nro_estab',$request->nro_estab)
                ->update([
                    "nro_credit_note"=>intval($request->sequential)
                ]);
        }else if($request->doc_type=='05'){
            Establishment::where('contributor_id',$request->contributor_id)
                ->where('nro_estab',$request->nro_estab)
                ->update([
                    "nro_debit_note"=>intval($request->sequential)
                ]);
        }else if($request->doc_type=='06'){
            Establishment::where('contributor_id',$request->contributor_id)
                ->where('nro_estab',$request->nro_estab)
                ->update([
                    "nro_guides"=>intval($request->sequential)
                ]);
        }else{
            Establishment::where('contributor_id',$request->contributor_id)
                ->where('nro_estab',$request->nro_estab)
                ->update([
                    "nro_retains"=>intval($request->sequential)
                ]);
        }

        //  Actualizamos el stock de productos
        if(isset($request->detail)){
            $items=[];
            $detalle=json_decode($request->detail);

            foreach($detalle as $item){
                $producto=Product::where('id',$item->codigo)->first();

                array_push($items,[
                    'name'=>$producto->name,
                    'notes'=>$producto->notes,
                    'quantity'=>$producto->quantity,
                    'complements'=>"",
                    'item_id'=>$producto->id
                ]);

                //  SI EL CONTEXTO ES ORDER: OBTENGO EL ID DEL PRODUCTO PRINCIPAL

                Product::where('id',$item->codigo)->update([
                    "quantity"=>intval($producto->quantity)-intval($item->cantidad)
                ]);
            }
        }

        $create_voucher=Voucher::create($data);
        $contributor=Contributor::where('id',$request->contributor_id)->first();

        //  Guardamos el RIDE
        $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode($request->access_key, $generator::TYPE_CODE_128,1.75);
        
        $invoice=Pdf::loadView('ride',[
            'items'=>$request->detail,
            'commercial_name'=>$contributor->commercial_name,
            'name'=>$contributor->name,
            'identification'=>$contributor->identification,
            'email'=>'',
            'access_key'=>$request->access_key,
            'sequential'=>$request->sequential,
            'direction'=>$contributor->direction,
            'logo'=>$contributor->logo_path,
            'date'=>$request->create_date,
            'phone'=>$contributor->phone,
            'regimen'=>$contributor->regimen,
            'oc'=>false,
            'client_name'=>$request->client_name,
            'client_ci'=>$request->client_identification,
            'client_email'=>$request->client_email,
            'client_direction'=>$request->client_direction,
            'subtotal'=>$request->subtotal_amount,
            'iva15'=>$request->tax_value,
            'iva5'=>0,
            'ice'=>0,
            'dscto'=>0,
            'total'=>$request->total_amount,
            'propina'=>0,
            'pay_ways'=>json_encode([
                [
                    'way'=>'SIN UTILIZACION DEL SISTEMA FINANCIERO',
                    'value'=>$request->total_amount,
                    'amount'=>30,
                    'way_time'=>'DIAS'
                ]
            ]),
            'adicional'=>json_encode([
                [
                    'field'=>'Telf',
                    'value'=>$request->client_phone
                ]
                ]),
            'barcode'=>$barcode
        ]);
        
        file_put_contents('ride_clients/'.$contributor->identification.'/'.$request->access_key.'.pdf', $invoice->output());

        // Enviamos el correo electrónico
        Mail::to($request->client_email)->send(new SendMailable);

        //  Enviamos a imprimir
        $data = http_build_query(array(
            'data'=>json_encode([
                'commercial_name'=>$contributor->commercial_name,
                'table'=>$request->client_mesa,
                'create_date'=>date('Y/m/d H:i:s',time()-18000),
                'items'=>$items,
                'nro_order'=>$create_voucher->id,
                'client_name'=>$request->client_name,
                'order_number_day'=>"",
                'contributor'=>$contributor,
                'context'=>"caja"
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
            "message"=>"Factura generada correctamente.",
            "data"=>$create_voucher
        ],200);
    }

    public function getMod11Dv(Request $request){
        $num=$request->clave_acceso;

        $digits = str_replace( array( '.', ',' ), array( ''.'' ), strrev($num ) );
        if ( ! ctype_digit( $digits ) )
        {
          return false;
        }

        $sum = 0;
        $factor = 2;

        for( $i=0;$i<strlen( $digits ); $i++ )
        {
          $sum += substr( $digits,$i,1 ) * $factor;
          if ( $factor == 7 )
          {
            $factor = 2;
          }else{
           $factor++;
         }
        }

        $dv = 11 - ($sum % 11);
        if ( $dv == 10 )
        {
          return 1;
        }
        if ( $dv == 11 )
        {
          return 0;
        }

        return $dv;
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $id)
    {
        $id->client=$id->find($id->id)->client;
        $id->contributor_identification=$id->find($id->id)->contributor;
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
