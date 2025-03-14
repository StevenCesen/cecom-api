<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Models\Itemcart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ========================= EndPoints para CONTRIBUYENTES ===============================
Route::get('/contributors',[ContributorController::class,'index']);
Route::get('/contributors/resume/{id}',[ContributorController::class,'showResumeStadistics']);
Route::get('/contributors/{id}',[ContributorController::class,'show']);
Route::post('/contributors',[ContributorController::class,'store']);
Route::put('/contributors/{id}',[ContributorController::class,'update']);
Route::put('/contributors/general/{id}',[ContributorController::class,'updateGeneral']);
Route::delete('/contributors/{id}',[ContributorController::class,'destroy']);

// ========================= EndPoints para ESTABLECIMIENTOS ===============================
Route::get('/establishments',[EstablishmentController::class,'index']);
Route::get('/contributors/{id}/establisments',[EstablishmentController::class,'index']);
Route::post('/establisments',[EstablishmentController::class,'store']);
Route::put('/establisments/{id}',[EstablishmentController::class,'update']);
Route::delete('/establisments/{id}',[EstablishmentController::class,'destroy']);

// ========================= EndPoints para USUARIOS ===============================
Route::post('/login',[LoginController::class,'login']);
Route::get('/contributors/{id}/users',[UserController::class,'index']);
Route::get('/users',[UserController::class,'index']);
Route::post('/contributors/{id}/users',[UserController::class,'store']);
Route::put('/users/{id}',[UserController::class,'update']);
Route::delete('/users/{id}',[UserController::class,'destroy']);

// ========================= EndPoints para PRODUCTOS ===============================
Route::get('/contributors/{id}/products',[ProductController::class,'index']);
Route::get('/products/{id}',[ProductController::class,'show']);
Route::post('/products',[ProductController::class,'store']);
Route::put('/products/{id}',[ProductController::class,'update']);
Route::delete('/products/{id}',[ProductController::class,'destroy']);

// ========================= EndPoints para MENÚS ===============================
Route::get('/contributors/{id}/menus',[MenuController::class,'index']);
Route::get('/menus/{id}',[MenuController::class,'show']);
Route::post('/menus',[MenuController::class,'store']);
Route::put('/menus/{id}',[MenuController::class,'update']);
Route::delete('/menus/{id}',[MenuController::class,'destroy']);

// ========================= EndPoints para ITEMS ===============================
Route::get('/contributors/{id}/items',[ItemController::class,'index']);
Route::get('/items/{id}',[ItemController::class,'show']);
Route::post('/items',[ItemController::class,'store']);
Route::put('/items/{id}',[ItemController::class,'update']);
Route::delete('/items/{id}',[ItemController::class,'destroy']);

// ========================= EndPoints para CATEGORÍAS ===============================
Route::get('/contributors/{id}/types',[TypeController::class,'index']);
Route::post('/types',[TypeController::class,'store']);

// ========================= EndPoints para CLIENTES ===============================
Route::get('/contributors/{id}/clients',[ClientController::class,'index']);
Route::get('/clients/{id}',[ClientController::class,'show']);
Route::post('/clients',[ClientController::class,'store']);
Route::put('/clients/{id}',[ClientController::class,'update']);
Route::delete('/clients/{id}',[ClientController::class,'destroy']);

// ========================= EndPoints para COMPROBANTES ===============================
Route::get('/contributors/{id}/vouchers',[VoucherController::class,'index']);
Route::get('/contributors/{id}/reports',[VoucherController::class,'report']);
Route::get('/vouchers/mod11',[VoucherController::class,'getMod11Dv']);
Route::get('/vouchers/{id}',[VoucherController::class,'show']);
Route::post('/vouchers',[VoucherController::class,'store']);
Route::put('/vouchers/{id}',[VoucherController::class,'update']);
Route::delete('/vouchers/{id}',[VoucherController::class,'destroy']);

// ========================= EndPoints para DOCUMENTOS ===============================
Route::get('/ride',[PDFController::class,'generateRIDE']);
Route::post('/sendInvoice',[MailController::class,'sendMail']);

// ========================= EndPoints para COMANDAS ===============================
Route::get('/contributors/{id}/ordersmesero',[OrderController::class,'indexMesero']);
Route::get('/contributors/{id}/orders',[OrderController::class,'index']);
Route::get('/orders/{id}',[OrderController::class,'show']);
Route::post('/orders/additems',[OrderController::class,'addItems']);
Route::post('/orders',[OrderController::class,'store']);
Route::put('/orders/{id}',[OrderController::class,'update']);
Route::delete('/orders/{id}',[OrderController::class,'destroy']);

// ========================= EndPoints para ITEMS CART ===============================
Route::post('/itemscart',[Itemcart::class,'store']);

Route::get('/printest',function(){
    $data=json_encode([
        "cliente_name"=>"JUANITO EL CARTERO"
    ]);

    $data = http_build_query(array(
        'data'=>$data,
        'public_ip'=>"10.8.0.2",
        'ip_cocina'=>"192.168.1.110",
        'ip_caja'=>"",
        'ip_barra'=>"",
        'nro_impresiones'=>2
    ));

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://srv479098.hstgr.cloud/connectvpn.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resultado= curl_exec($ch);
    curl_close($ch);

    return $resultado;
});