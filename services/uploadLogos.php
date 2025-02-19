<?php

// Recibimos el certificado
$file=$_FILES['logo'];

// Guardamos el certificado
move_uploaded_file($file['tmp_name'],'../public/logos/'.$_POST['logo_path']);

//Respondemos al frontend
echo json_encode([
    "status"=>200
]);