<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SelectController;
use App\Http\Controllers\UserGuessController;
use App\Http\Controllers\ComputerGuessController;
use App\Http\Controllers\ComputerSelectController;
use App\Http\Controllers\ObjectController;
use App\Http\Controllers\StatisticsController;
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

//set the language the user wants to learn
Route::get('/learn-language', [SelectController::class, 'getLearnlanguage']);
Route::get('/learn-language/{locale}', [SelectController::class, 'storeLearnlanguage']);

//GET list of objects
Route::get('/objects', [ObjectController::class, 'index']);

//POST object chosen
//computer also randomly choses object
Route::post('/select', [SelectController::class, 'store']);

//POST object chosen
//set computer object (for test purposes)
Route::post('/computer-select', [ComputerSelectController::class, 'store']);

//GET object chosen
Route::get('/select', [SelectController::class, 'show']);

//GET computer guesses attribute
Route::get('/computer-guess', [ComputerGuessController::class, 'index']);

//Computer return matching objects
Route::post('/computer-guess', [ComputerGuessController::class, 'store']);

//GET all available attributes user can choose from
Route::get('/remaining-attributes', [UserAttributesController::class, 'index']);

//GET all remaining objects as array of names
Route::get('/remaining-objects', [UserGuessController::class, 'remainingObjects']);

//GET sentence(s) using a random question type and guessed attribute
Route::get('/user-guess', [UserGuessController::class, 'index']);

//User POST guessed attribute
//returns "correct": bool
Route::post('/user-guess/verify-attribute', [UserGuessController::class, 'verifyAttribute']);

//User POST guessed sentence
//returns "correct": bool
Route::post('/user-guess/verify-sentence', [UserGuessController::class, 'verifySentence']);

//User POST guessed attribute
//returns correct sentence
Route::get('/user-guess/correct-sentence', [UserGuessController::class, 'correctSentence']);

//User POST guessed attribute
//returns correct or false
Route::post('/user-guess', [UserGuessController::class, 'attribute']);

//User POST guessed object
//returns boolean
Route::post('/user-guess/object', [UserGuessController::class, 'object'])
    ->middleware('customthrottle:rate_limit,1');

Route::get('/stats/visitsday', [StatisticsController::class, 'visitsday']);
Route::get('/stats/leaderboard', [StatisticsController::class, 'leaderboard']);