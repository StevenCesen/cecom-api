<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generateRIDE(){
        $items=[
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1 CON CABLE USB Y MONTAJE',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PLACA DE DESARROLLO ESP32 CAM WIFI Y BLUETOOTH CAMARA OV2640',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            )
        ];

        $pay_way=[
            [
                'way'=>'SIN UTILIZACION DEL SISTEMA FINANCIERO',
                'value'=>10.00,
                'amount'=>30,
                'way_time'=>'DIAS'
            ],
            [
                'way'=>'CON UTILIZACION DEL SISTEMA FINANCIERO',
                'value'=>7.25,
                'amount'=>30,
                'way_time'=>'DIAS'
            ]
        ];

        $adicional=[
            [
                'field'=>'nota',
                'value'=>'P/R CONSUMO EN ESTABLECIMIENTO'
            ],
            [
                'field'=>'pago',
                'value'=>'7.25 CON CODIGO AHORITA 74469698'
            ]
        ];

        $data=[
            'items'=>json_encode($items),
            'commercial_name'=>'INTELIVE',
            'name'=>'CESEN PACCHA STEVEN RAFAEL',
            'identification'=>'1150575338001',
            'email'=>'steven.r.cesen@hotmail.com',
            'access_key'=>'1602202501115057533800110010010000001551224567811',
            'sequential'=>'001-001-000000155',
            'direction'=>'AV. MANUEL AGUSTIN AGUIRRE Y MAXIMILIANO RODRIGUEZ',
            'date'=>'2025-01-27T10:32:01',
            'phone'=>'0978950498',
            'regimen'=>'GENERAL',
            'oc'=>false,
            'client_name'=>'CALDERON ORDOÑEZ MARIA ANTONIETA',
            'client_ci'=>'1100660032',
            'client_email'=>'wilson@gmail.com',
            'client_direction'=>'AV. DE LAS AMERICAS',
            'subtotal'=>15.00,
            'iva15'=>2.25,
            'iva5'=>0,
            'ice'=>0,
            'dscto'=>0,
            'total'=>17.25,
            'propina'=>0,
            'pay_ways'=>json_encode($pay_way),
            'adicional'=>json_encode($adicional)
        ];

        $invoice=Pdf::loadView('ride',$data);
        
        return $invoice->output();
    }
}
