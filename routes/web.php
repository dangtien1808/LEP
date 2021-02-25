<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DropzoneController;
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

Route::get('/', function(){
   return view('welcome');
});
//Route::get('/', [DropzoneController::class, 'index']); 

Route::get('upload', [DropzoneController::class, 'index']);
Route::get('upload/fetch', [DropzoneController::class, 'fetch'])->name('upload.fetch');
Route::post('images/delete', [DropzoneController::class, 'delete'])->name('images.delete');
Route::post('upload/handle', [DropzoneController::class, 'upload'])->name('upload.handle');
