<?php

use App\Http\Controllers\ShirtOrderController;
use App\Models\ShirtOrder;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::post('/tshirt', [ShirtOrderController::class, 'store'])
    ->middleware('throttle:6,1')          // max 6 försök per minut och IP
    ->name('shirts.store');

Route::get('/tshirt/tack/{reference}', [ShirtOrderController::class, 'thanks'])
    ->name('shirts.thanks');

// Enkel adminvy — skyddad med hemlig nyckel ur .env, ingen inloggning behövs.
// Saknas nyckeln stängs vyn helt: annars hade hash_equals() jämfört två tomma
// strängar och släppt in vem som helst utan ens en query-parameter.
Route::get('/admin/bestallningar', function () {
    $nyckel = (string) config('festival.admin_key');

    abort_unless(
        filled($nyckel) && hash_equals($nyckel, (string) request('nyckel')),
        403
    );

    return view('admin.orders', [
        'orders'  => ShirtOrder::latest()->get(),
        'summary' => ShirtOrder::printSummary(),
    ]);
})->name('admin.orders');
