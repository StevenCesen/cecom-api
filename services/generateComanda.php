<?php

require __DIR__ . '/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
// use Mike42\Escpos\EscposImage;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

$nombre_impresora = "smb";
$data=json_decode($_GET['data']);

$connector = new WindowsPrintConnector("smb://DESKTOP-5PJNCRE/POS-80");
$printer = new Printer($connector);

$printer->setJustification(Printer::JUSTIFY_CENTER);

$printer->setTextSize(2, 2);
$printer->text("Comanda #".$data->id);

$printer->setTextSize(1, 1);
$printer->text("\n".date('Y/m/d H:i:s',time()-25100));

$printer->setTextSize(2, 1);
$printer->text("\nMesa #".$data->mesa." - ".$data->piso);
$printer->text("Cliente: ".$data->name_client);
$printer->feed(2);

$printer->setTextSize(1, 1);

$printer->setJustification(Printer::JUSTIFY_LEFT);

foreach(json_decode($data->details) as $item){
    $printer->text("\n ".$item->cantidad."  ===> ".$item->name."\n");
}

$printer->feed(2);

$printer->setJustification(Printer::JUSTIFY_CENTER);

$printer->setTextSize(2, 1);
$printer->text("\nNOTAS:\n");

$printer->setTextSize(1, 1);
$printer->text("\n".$data->notes."\n");

$printer->feed(2);

$printer->setJustification(Printer::JUSTIFY_LEFT);

$printer->setTextSize(1, 1);
$printer->text("\n Generado por: ".$data->byUser."\n");

$printer->feed(2);

$printer->cut();
$printer->pulse();
$printer->close();

echo json_encode(array(
    "state"=>200,
    "data"=>$data
));
