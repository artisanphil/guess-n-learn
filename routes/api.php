<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SelectController;
use App\Http\Controllers\UserGuessController;
use App\Http\Controllers\ComputerGuessController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//GET list of objects
Route::get('/index', [SelectController::class, 'index']);

//POST person chosen
//computer also randomly choses person
Route::post('/select', [SelectController::class, 'store']);

//GET computer guesses characteristic
Route::get('/computer-guess', [ComputerGuessController::class, 'index']);

//Computer return matching characters
Route::post('/computer-guess', [ComputerGuessController::class, 'store']);

//User POST guessed characteristic
//returns matching characters
Route::post('/user-guess', [UserGuessController::class, 'store']);
