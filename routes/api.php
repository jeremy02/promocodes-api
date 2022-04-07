<?php

use App\Http\Controllers\PromoCodeController;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// our apis don't need authentication
Route::post('promocodes/checkvalid', [PromoCodeController::class, 'checkvalid']);
// Route::put('promocodes/restore/{promocode}', [PromoCodeController::class, 'restore']); // restore soft deleted records
Route::put('promocodes/activate/{promocode}', [PromoCodeController::class, 'activatePromoCode']); // activate a promo code
Route::put('promocodes/deactivate/{promocode}', [PromocodeController::class, 'deActivatePromoCode']); // de-activate a promo code
Route::get('promocodes/active', [PromoCodeController::class, 'activePromoCodes']); // get all the active promo codes
Route::get('promocodes/inactive', [PromoCodeController::class, 'inActivePromoCodes']); // get all the in-active promo codes
Route::resource('promocodes', PromoCodeController::class);
