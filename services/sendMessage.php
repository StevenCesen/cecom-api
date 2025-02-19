<?php

$header="Content-type: application/json\r\n".
        "Accept: application/json\r\n".
        "Content-Type: multipart/form-data\r\n".
        "Authorization: Bearer b7ac3bab-e2ab-49ac-b772-4b0f30887246";

$datos=array(
    'number'=>$_POST['number'],
    'body'=>$_POST['body'],
    'medias'=>$_FILES['ride']
);

$url_ws_create="http://localhost:8000/api/messages/send";

$opciones = array(
    "http" => array(
        "header" => $header,
        "method" => "POST",
        "content" => json_encode($datos)
    ),
);

$contexto = stream_context_create($opciones);
$resultado = file_get_contents($url_ws_create, false, $contexto);

if($resultado){
    echo json_encode([
        "status"=>200,
        "file"=>$_FILES['ride']['tmp_name']
    ]);
}else{
    echo json_encode([
        "status"=>400,
        "file"=>json_encode($_FILES['ride'])
    ]);
}
