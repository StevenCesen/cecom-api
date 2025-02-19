<?php

// Recibimos el certificado
$file=$_FILES['file'];

// Guardamos el certificado
move_uploaded_file($file['tmp_name'],'../public/certs/'.$_POST['sign_path']);

echo json_encode([
    "status"=>200
]);

