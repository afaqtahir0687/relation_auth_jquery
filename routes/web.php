<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/',[App\Http\Controllers\AuthController::class, 'register']);
Route::post('register.store',[App\Http\Controllers\AuthController::class, 'Register_store']);
Route::get('dashboard',[App\Http\Controllers\AuthController::class, 'dashboard']);
Route::get('login',[App\Http\Controllers\AuthController::class, 'login']);
Route::post('login.store',[App\Http\Controllers\AuthController::class, 'Login_store']);
Route::get('logout',[App\Http\Controllers\AuthController::class, 'logout']);

Route::get('/category/index', [App\Http\Controllers\CategoryController::class, 'index'])->name('category.index');
Route::get('/category/create',[App\Http\Controllers\CategoryController::class, 'create'])->name('category.create');
Route::post('category/store',[App\Http\Controllers\CategoryController::class, 'store'])->name('category.store');
Route::get('category/{id}/edit',[App\Http\Controllers\CategoryController::class, 'edit'])->name('category.edit');
Route::delete('category/delete/{id}',[App\Http\Controllers\CategoryController::class, 'destroy'])->name('category.delete');

Route::get('/products/products',[ProductController::class, 'create'])->name('products.create');
Route::get('/products/index', [ProductController::class, 'index'])->name('products.index');
Route::post('products/store', [ProductController::class, 'store'])->name('products.store');
Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::delete('/products/delete/{id}', [ProductController::class, 'destroy'])->name('products.delete');




