<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('barcode', function () {
    return response((new Picqer\Barcode\BarcodeGeneratorPNG())->getBarcode('hahaha', (new Picqer\Barcode\BarcodeGeneratorPNG())::TYPE_CODE_128))->header('Content-type','image/png');
});