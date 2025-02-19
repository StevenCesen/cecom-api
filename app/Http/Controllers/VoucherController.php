<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contributor;
use App\Models\Establishment;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;

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
            $detalle=json_decode($request->detail);

            foreach($detalle as $item){
                $producto=Product::where('id',$item->codigo)->first();
                Product::where('id',$item->codigo)->update([
                    "quantity"=>intval($producto->quantity)-intval($item->cantidad)
                ]);
            }
        }

        $create_voucher=Voucher::create($data);

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
