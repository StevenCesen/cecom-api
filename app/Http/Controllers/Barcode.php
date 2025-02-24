<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Barcode extends Controller
{
    public function barcodeIndex(Request $request)
    {
        $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode('0001245786925', $generator::TYPE_CODE_128);
  
        return view('barcode', data: compact('barcode'));
    }
}
