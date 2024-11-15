<?php

use App\Http\Controllers\SellingController;
use App\Models\Selling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});





Route::get('generate-QR-code', function(Request $request){
    // generate qrcode

    $selling = Selling::whereId($request->bill)->first();

    $bill_id = $selling->id;
    $shop_name = 'شركة سليمان الزهيري المحدودة';
    $vat_number = '310451998500003';
    $sale_date = $selling->created_at;
    $net_price = $selling->total;
    $vat = $selling->vat;
    // data:image/png;base64, .........
    $displayQRCodeAsBase64 = GenerateQrCode::fromArray([
        new Seller( $shop_name ), // seller name
        new TaxNumber( $vat_number ), // seller tax number
        new InvoiceDate( $sale_date ), // invoice date as Zulu ISO8601 @see https://en.wikipedia.org/wiki/ISO_8601
        new InvoiceTotalAmount( $net_price ), // invoice total amount
        new InvoiceTaxAmount( $vat ) // invoice tax amount
        // TODO :: Support others tags
    ])->render();


    return view('QR-code', compact('displayQRCodeAsBase64'));

})->name('generate-QR-code');



