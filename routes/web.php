<?php

use App\Http\Controllers\BatchController;
use App\Http\Controllers\FileImportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::redirect('/', '/upload-file');
Route::get('/upload-file', function () {
    return view('file-upload');
});
Route::post('/upload-file', FileImportController::class)->name('fileUpload');
Route::get('/batch/{batch}', BatchController::class);
