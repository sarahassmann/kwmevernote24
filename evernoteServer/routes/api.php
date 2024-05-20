<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KwmlistController;
use App\Http\Controllers\KwmnoteController;
use App\Http\Controllers\KwmtagController;
use App\Http\Controllers\KwmtodoController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route middleware for JWT Auth and JWT Refresh to get a new token and a user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes for KWM-Lists
Route::get('kwmlists',[KwmlistController::class, 'index']);
Route::get('kwmlists/{id}',[KwmlistController::class, 'findById']);
Route::get('kwmlists/checkId/{id}',[KwmlistController::class, 'checkId']);
Route::get('kwmlists/search/{searchTerm}',[KwmlistController::class, 'findBySearchTerm']);


// Routes for KWM-Notes
Route::get('kwmnotes', [KwmnoteController::class, 'index']);
Route::get('kwmnotes/{id}', [KwmnoteController::class, 'findByNotesId']);
Route::get('kwmnotes/checkNoteId/{id}', [KwmnoteController::class, 'checkNoteId']);
Route::get('kwmnotes/search/{searchTerm}', [KwmnoteController::class, 'findBySearchTerm']);


// Routes for KWM-Tags
Route::get('kwmtags', [KwmtagController::class, 'index']);
Route::get('kwmtags/{id}', [KwmtagController::class, 'findById']);
Route::get('kwmtags/checkTagId/{id}', [KwmtagController::class, 'checkTagId']);
Route::get('kwmtags/search/{searchTerm}', [KwmtagController::class, 'findBySearchTerm']);

// Routes for KWM-Todos
Route::get('kwmtodos', [KwmtodoController::class, 'index']);
Route::get('kwmtodos/{id}', [KwmtodoController::class, 'findTodo']);
Route::get('kwmtodos/checkTodoId/{id}', [KwmtodoController::class, 'checkId']);
Route::get('kwmtodos/search/{searchTerm}', [KwmtodoController::class, 'findBySearchTerm']);
Route::post('kwmtodos', [KwmtodoController::class, 'save']);
Route::delete('kwmtodos/{id}', [KwmtodoController::class, 'delete']);
Route::put('kwmtodos/{id}', [KwmtodoController::class, 'update']);

// Routes for Users
Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}', [UserController::class, 'findById']);
Route::get('users/checkUserId/{id}', [UserController::class, 'checkUserId']);
Route::get('users/search/{searchTerm}', [UserController::class, 'findBySearchTerm']);
Route::post('users', [UserController::class, 'save']);
Route::delete('users/{id}', [UserController::class, 'delete']);
Route::put('users/{id}', [UserController::class, 'update']);

// Routes for Authentication and Authorization (JWT) only for logged-in users
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::group(['middleware' => ['api', 'auth.jwt']], function(){
    Route::post('kwmlists', [KwmlistController::class, 'save']);
    Route::delete('kwmlists/{id}', [KwmlistController::class, 'delete']);
    Route::put('kwmlists/{id}', [KwmlistController::class, 'update']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('kwmnotes', [KwmnoteController::class, 'save']);
    Route::delete('kwmnotes/{id}', [KwmnoteController::class, 'delete']);
    Route::put('kwmnotes/{id}', [KwmnoteController::class, 'update']);
    Route::post('kwmtags', [KwmtagController::class, 'save']);
    Route::delete('kwmtags/{id}', [KwmtagController::class, 'delete']);
    Route::put('kwmtags/{id}', [KwmtagController::class, 'update']);
    Route::post('kwmtodos', [KwmtodoController::class, 'save']);
    Route::delete('kwmtodos/{id}', [KwmtodoController::class, 'delete']);
    Route::put('kwmtodos/{id}', [KwmtodoController::class, 'update']);
});



