<?php

use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\SubscriberController;
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

//API Key routes
Route::get('/', [ApiKeyController::class, 'view'])->name('manage-api-key');
Route::post('/', [ApiKeyController::class, 'store']);

//Subscriber routes
//List
Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscriber-index');
Route::post('/subscribers', [SubscriberController::class, 'data']);
//Edit
Route::get('/subscribers/{id}/edit', [SubscriberController::class, 'edit'])->name('subscriber-edit');
Route::put('/subscribers/{id}/edit', [SubscriberController::class, 'update']);
//Create
Route::get('/subscribers/create', [SubscriberController::class, 'create'])->name('subscriber-create');
Route::post('/subscribers/create', [SubscriberController::class, 'new']);
//Delete
Route::delete('/subscribers/{id}/delete', [SubscriberController::class, 'delete'])->name('subscriber-delete')->where(['id' => '[0-9]+']);
