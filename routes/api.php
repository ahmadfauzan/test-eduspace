<?php

use App\Http\Controllers\API\TransactionController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('transactions')->name('transaction.')->group(function () {
    Route::get('', [TransactionController::class, 'fetch'])->name('fetch');
    Route::post('', [TransactionController::class, 'create'])->name('create');
    Route::put('/{id}', [TransactionController::class, 'update'])->name('update');
    Route::delete('/{id}', [TransactionController::class, 'destroy'])->name('destroy');
});
