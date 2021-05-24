<?php

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

Route::get('/', function () {
    return view('home');
});
Route::get('/about', function () {
    return view('about');
});
Route::get('/opportunity', function () {
    return view('opportunity');
});
Route::get('/testimonial', function () {
    return view('testimonial');
});
Route::get('/research', function () {
    return view('research');
});
Route::get('/contact', function () {
    return view('contact');
});
Route::get('/nutritional', function () {
    return view('nutritional');
});
Route::get('/beverage', function () {
    return view('beverage');
});
Route::get('/rare-oil/{id}', function ($id) {
    return view('rare-oil', compact('id'));
});
Route::get('/element', function () {
    return view('element');
});
Route::get('/product-details2/{product_id}', function ($product_id) {
    return view('product-details2', compact('product_id'));
});
Route::get('/product-details3/{product_id}', function ($product_id) {
    return view('product-details3', compact('product_id'));
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
