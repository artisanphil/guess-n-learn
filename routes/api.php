<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SelectController;
use App\Http\Controllers\UserGuessController;
use App\Http\Controllers\ComputerGuessController;
use App\Http\Controllers\UserAttributesController;

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

//POST object chosen
//computer also randomly choses object
Route::post('/select', [SelectController::class, 'store']);

//GET object chosen
Route::get('/select', [SelectController::class, 'show']);

//GET computer guesses attribute
Route::get('/computer-guess', [ComputerGuessController::class, 'index']);

//Computer return matching objects
Route::post('/computer-guess', [ComputerGuessController::class, 'store']);

//GET all available attributes user can choose from
Route::get('/remaining-attributes', [UserAttributesController::class, 'index']);

//GET sentence(s) using a random question type and guessed attribute
Route::get('/user-guess', [UserGuessController::class, 'index']);

//User POST guessed attribute
//returns matching objects
Route::post('/user-guess', [UserGuessController::class, 'store']);
