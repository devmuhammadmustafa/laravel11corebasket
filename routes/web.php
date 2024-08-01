<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('/content', 'content');
    Route::get('/get/{id}', 'get');
    Route::get('/add', 'add');
    Route::get('/add-multiple', 'addMultiple');
    Route::get('/delete/{id}', 'delete');
    Route::get('/clear', 'clear');
    Route::get('/total', 'total');
    Route::get('/subtotal', 'subtotal');
    Route::get('/tax', 'getTax');
    Route::get('/set-tax/{tax}', 'setTax');
    Route::get('/count', 'count');
    Route::get('/update/{id}/{qty}', 'update');

});

Route::get('/aaa', function (){
    $cart = new CartController();
    $cart->setTax(21);
    echo $cart->total();
});
