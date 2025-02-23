<?php

require __DIR__ . '/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
// use Mike42\Escpos\EscposImage;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

$nombre_impresora="smb";
$data=json_decode($_GET['data']);

$connector = new WindowsPrintConnector("smb://DESKTOP-5PJNCRE/POS-80");
$printer = new Printer($connector);

$printer->setJustification(Printer::JUSTIFY_CENTER);

$printer->setTextSize(3, 2);

$printer->text($data->commercial_name);
$printer->feed(2);
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->setTextSize(1, 1);
$printer->text("\n Razón Social: $data->name");
$printer->text("\n RUC: $data->identification");
$printer->text("\n Teléfono: $data->phone");
$printer->text("\n Email: $data->email");
$printer->text("\n Fecha: ".date('Y/m/d H:i:s',time()-25100));

$printer->feed(2);

$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->setTextSize(1, 1);
$printer->text("\nCliente: ".$data->name);
$printer->text("\nRuc/Ci: ".$data->ci);
$printer->text("\nCorreo: ".$data->email);

$printer->feed(2);

$printer->setTextSize(1, 1);
$printer->setJustification(Printer::JUSTIFY_LEFT);

$total=0;

foreach($data->details as $item){
    $total=floatval($item->cantidad)*floatval($item->precio);
    $printer->text("\n ".$item->cantidad."  => ".$item->name." => $ ".floatval($item->precio)*intval($item->cantidad)."\n");
}

$printer->setTextSize(1, 1);
$printer->setJustification(Printer::JUSTIFY_RIGHT);
$printer->text("\nTotal: ".round($data->total,2));

$printer->setJustification(Printer::JUSTIFY_CENTER);

$printer->setTextSize(2, 1);
$printer->text("\nGRACIAS POR PREFERIRNOS");
$printer->feed(2);

$printer->cut();
$printer->pulse();
$printer->close();

echo json_encode(array(
    "state"=>200,
    "data"=>$data
));
