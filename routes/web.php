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
Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscriber-index');
Route::get('/subscribers/1/edit', [SubscriberController::class, 'edit'])->name('subscriber-edit');
Route::get('/subscribers/create', [SubscriberController::class, 'create'])->name('subscriber-create');
Route::get('/subscribers/1/delete', [SubscriberController::class, 'delete'])->name('subscriber-delete');
