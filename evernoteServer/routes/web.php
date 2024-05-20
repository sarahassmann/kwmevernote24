<?php

use App\Models\Kwmlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// when after '/' nothing specific -> show the root-index site (index.blade.php)

// Routes for KWM-Lists
Route::get('/', [\App\Http\Controllers\KwmlistController::class, "index"]);
Route::get('/kwmlists', [\App\Http\Controllers\KwmlistController::class, "index"]);

// Route for showing a specific KWM-List
Route::get('/kwmlists/{id}', function ($id) {
    $kwmlist = Kwmlist::find($id);
    return view('kwmlists.show', compact('kwmlist'));
});

// Routes for KWM-Notes
Route::get('/kwmnotes', [\App\Http\Controllers\KwmnoteController::class, "index"]);
