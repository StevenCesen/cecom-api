<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\User;
use Illuminate\Http\Request;
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
        $user->cert=base64_encode(file_get_contents('certs/'.$id->signature_path));
        $user->establishment=$user->find($user->id)->establishment;
        
        //Usuario más reciente
        //$user_reciente=Contributor::find($request->id)->largestUser;
        
        return $user;
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
