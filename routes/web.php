<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/checkout',[PaymentController::class,'showCheckoutForm']);
Route::get('/payment', function () {
    return view('payment');
});
Route::get('/account', function () {
    return view('account');
});
Route::get('/card', function () {
    return view('card');
});
Route::post('/create-payment', [PaymentController::class, 'createABAPaymentLink'])->name('payment.create');
Route::post('/create-account', [PaymentController::class, 'createAccount'])->name('payment.account');
Route::post('/create-card-on-file', [PaymentController::class, 'createCardOnFile'])->name('payment.card');
Route::get('/generate-khqr', [PaymentController::class, 'generate'])->name('payment.khqr');
// Route::get('/create-card', [PaymentController::class, 'createCardOnFile']);
Route::get('/payment-success', function () {
    return 'Payment was successful!';
})->name('payment.success');

Route::get('/payment-cancel', function () {
    return 'Payment was canceled!';
})->name('payment.cancel');
