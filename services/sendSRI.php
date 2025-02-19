<?php

require_once '../lib/nusoap.php';

/*
====================================================================================================================================================

                                 MODULO v1.0.0 PARA RECEPTAR Y AUTORIZAR DOCUMENTOS EN PRUEBA Y PRODUCCIÓN

====================================================================================================================================================
*/

// 1) Válidamos que el contribuyente este registrado en el sistema, tenga una suscripción y este dentro del plan de la suscripción.
$url_recepcion='';

if($_POST['ambiente']=='1'){
    $url_recepcion="https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";
}else{
    $url_recepcion="https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";
}

try {
    // 2) Enviamos el documento a recepción del SRI
    $client=new nusoap_client($url_recepcion,true);
    $client->soap_defencoding = 'utf-8';
    $client->xml_encoding = 'utf-8';
    $client->decode_utf8 = false;

    // 3) Obtengo la data relacionada: XML, CLAVE DE ACCESO y PDF
    $data['xml']=$_POST['xml'];
    $clave_acceso=$_POST['clave_acceso'];
    $response=$client->call('validarComprobante',$data);

    if($response['RespuestaRecepcionComprobante']['estado']=='RECIBIDA'){

        // 4) Guardamos el XML FIRMADO
        // file_put_contents("public/xml_clients/".$_POST['ruc']."/"."autorizados/".$clave_acceso.'.xml',base64_decode($_POST['xml']));

        // 5) Devolvemos OK
        echo json_encode([
            "status"=>200,
            "response"=>$response,
            "message"=>"SRI: Comprobante recibido.",
            "url"=>$url_recepcion,
            "clave_acceso"=>$clave_acceso
        ]);

    }else if($response['RespuestaRecepcionComprobante']['estado']=='DEVUELTA'){
        if($response['RespuestaRecepcionComprobante']['comprobantes']['comprobante']['mensajes']['mensaje']['mensaje']=="CLAVE ACCESO REGISTRADA"){

            // 6) Devolvemos FAIL
            echo json_encode([
                "status"=>"REPEAT",
                "response"=>$response,
                "message"=>"SRI: Clave de acceso registrada.",
                "url"=>$url_recepcion,
                "clave_acceso"=>$clave_acceso
            ]);

        }else{

            echo json_encode([
                "status"=>"ERR_XML",
                "response"=>$response,
                "message"=>"SRI: Formato o secuencial inconsistente.",
                "url"=>$url_recepcion,
                "clave_acceso"=>$clave_acceso
            ]);

        }
    }else{

        echo json_encode([
            "status"=>400,
            "response"=>$response,
            "message"=>"SRI: No se completó la solicitud",
            "url"=>$url_recepcion,
            "clave_acceso"=>$clave_acceso
        ]);

    }

} catch (\Throwable $th) {
    echo json_encode([
        "status"=>"ERR_SVR",
        "response"=>$response,
        "message"=>"SRI: comunicación fallida.",
        "url"=>$url_recepcion,
        "clave_acceso"=>$clave_acceso
    ]);
}
