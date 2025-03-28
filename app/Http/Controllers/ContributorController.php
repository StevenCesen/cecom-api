<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ContributorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Contributor::get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        mkdir('ride_clients/'.$request->input('identification'), 0777, true);
        mkdir('xml_clients/'.$request->input('identification'), 0777, true);
        mkdir('xml_clients/'.$request->input('identification').'/no_autorizados', 0777, true);
        mkdir('xml_clients/'.$request->input('identification').'/pendientes', 0777, true);
        mkdir('xml_clients/'.$request->input('identification').'/autorizados', 0777, true);

        $create_contributor=Contributor::create($request->all());

        //  Creamos el usuario con los datos del controbuyente
        $create_user=User::create([
            'name'=>$request->name,
            'email' =>$request->email,
            'password' => Hash::make($request->identification),
            'contributor_id'=>$create_contributor->id,
            "role"=>1
        ]);

        return response()->json([
            "status"=>200,
            "message"=>"Contribuyente creado correctamente.",
            "data"=>$create_contributor
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,Contributor $id)
    {   
        //echo Contributor::findOrFail($id)->users;
        $user=$id->find($id->id)->user[0];
        $user->cert=($id->certificate!=null) ? base64_encode(file_get_contents('certs/'.$id->signature_path)) : "";
        $user->establishment=$user->find($user->id)->establishment;
        
        //Usuario más reciente
        //$user_reciente=Contributor::find($request->id)->largestUser;
        
        return $user;
    }

    public function showResumeStadistics(Request $request,Contributor $id)
    {

        $now=date('Y-m-d',time()-18000);
        $yesterday=explode('-',date('Y-m-d',time()-18000));
        $yesterday=$yesterday[0].'-'.$yesterday[1].'-'.(($yesterday[2]-1<10) ? '0'.$yesterday[2]-1 : $yesterday[2]-1);
        $month=date('Y-m',time()-18000);

        $id->total_now=round($id->find($id->id)->voucher()->where('create_date','REGEXP',$now)->sum('total_amount'),2);
        $id->total_yesterday=round($id->find($id->id)->voucher()->where('create_date','REGEXP',$yesterday)->sum('total_amount'),2);
        $id->total_month=round($id->find($id->id)->voucher()->where('create_date','REGEXP',$month)->sum('total_amount'),2);
        $id->products=$id->find($id->id)->product()->count();
        $id->clients=$id->find($id->id)->client()->count();
        $id->vouchers=$id->find($id->id)->voucher()->count();

        return $id;
    }

    public function showProductsStadistics(){
        DB::statement("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");
        
        $fecha = date('Y/m',time()-18000);
        $results = DB::table('orders as o')
            ->join('itemcarts as i', 'i.order_id', '=', 'o.id')
            ->join('products as p', 'p.id', '=', 'i.item_id')
            ->select(DB::raw('p.name as Producto'), DB::raw('COUNT(i.item_id) as Cantidad'))
            ->where('o.contributor_id', 6)
            ->whereRaw('o.create_date REGEXP ?', [$fecha]) // Usamos el parámetro en lugar de la cadena directa
            ->groupBy('i.item_id')
            ->orderByRaw('COUNT(i.item_id) DESC')
            ->get();

        return $results;
    }

    public function bchexdec($hex){
        $dec = 0;
        $len = strlen($hex);
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }
        return $dec;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contributor $id)
    {
        $data=[
            'signature_path'=>$request->sign_path,
            'x509_serial_number'=>$this->bchexdec($request->X509SerialNumber),
            'x509_der_hash'=>$request->certificateX509_der_hash,
            'exponent'=>$request->exponent,
            'module'=>$request->modulus,
            'issuer_name'=>$request->IssuerName,
            'validity_date'=>$request->validity,
            'x509_self'=>$request->x509_self,
            'certificate'=>$request->certificateX509
        ];

        $update_contributor=$id->update($data);

        return response()->json([
            "status"=>200,
            "message"=>"Contribuyente actualizado correctamente.",
            "data"=>$update_contributor
        ],200);
    }

    public function updateGeneral(Request $request, Contributor $id)
    {
        $update_contributor=$id->update($request->all());

        return response()->json([
            "status"=>200,
            "message"=>"Contribuyente actualizado correctamente.",
            "data"=>$update_contributor
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contributor $id)
    {
        $delete_contributor=$id->delete();

        return response()->json([
            "status"=>200,
            "message"=>"Contribuyente eliminado."
        ],200);

    }
}
