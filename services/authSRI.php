<?php

require_once '../lib/nusoap.php';
// 1) Seleccionamos el ambiente
$url_auth='';

if($_POST['ambiente']=='1'){
    $url_auth="https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
}else{
    $url_auth="https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
}

$client = new nusoap_client($url_auth, true);
$client->soap_defencoding = 'utf-8';
$client->xml_encoding = 'utf-8';
$client->decode_utf8 = false;

$clave_acceso=$_POST['clave_acceso'];
$ruc=$_POST['ruc'];
$data_xml=$_POST['xml'];

// 2) Enviamos a autorizar
$data['claveAccesoComprobante']=$clave_acceso;

$responseAut = $client->call('autorizacionComprobante', $data);

if($responseAut===false){

    echo json_encode([
        "status"=>400,
        "response"=>$responseAut,
        "estado"=>"ERR_SVR",
        "url"=>$url_auth,
        "message"=>'SRI: Error en la conexión.'
    ]);

}else if($responseAut['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado']=='AUTORIZADO'){

    $autorizacion = $responseAut['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion'];
    $estado = $autorizacion['estado'];
    $numeroAutorizacion = $autorizacion['numeroAutorizacion'];
    $fechaAutorizacion = $autorizacion['fechaAutorizacion'];
    $comprobanteAutorizacion = $autorizacion['comprobante'];

    //  Si el comprobante está autorizado lo guardamos
    //LOCAL
    //file_put_contents("public/xml_clients/".$ruc."/"."autorizados/".$clave_acceso.'.xml',base64_decode($data_xml));

    //PRODUCCION
    //file_put_contents("../public/xml_clients/".$ruc."/"."autorizados/".$clave_acceso.'.xml',base64_decode($data_xml));

    echo json_encode([
        "status"=>200,
        "estado"=>$estado,
        "message"=>'SRI: Comprobante autorizado.',
        "response"=>$responseAut,
        "numero_auth"=>$numeroAutorizacion,
        "fecha_auth"=>$fechaAutorizacion,
        "comprobante"=>$comprobanteAutorizacion
    ]);

}else if($responseAut['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado']=='EN PROCESO'){

    //  Si el comprobante está autorizado lo guardamos
    //file_put_contents("../public/xml_clients/".$ruc."/"."pendientes/".$clave_acceso.'.xml',base64_decode($data_xml));

    echo json_encode([
        "status"=>200,
        "estado"=>"EN PROCESO",
        "message"=>'SRI: Comprobante en proceso de autorización.',
        "response"=>$responseAut
    ]);
    
}else{

    //  Si el comprobante está autorizado lo guardamos
    //file_put_contents("../public/xml_clients/".$ruc."/"."no_autorizados/".$clave_acceso.'.xml',base64_decode($data_xml));

    echo json_encode([
        "status"=>400,
        "response"=>$responseAut,
        "url"=>$url_auth,
        "estado"=>"ERR_SVR"
    ]);

}