<?php

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
    return view('auth.login');
});

Auth::routes();

Route::match(["GET", "POST"], "/register", function(){
    return redirect("/login");
})->name("register");

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('users', 'UsersController');


//Categories route
Route::get('/categories/trash','CategoryController@trash')->name('categories.trash');
Route::get('/categories/{id}/restore','CategoryController@restore')->name('categories.restore');
Route::delete('/categories/{id}/permanent-delete','CategoryController@deletePermanent')->name('categories.permanent-delete');
Route::resource('categories', 'CategoryController');
Route::get('/ajax/categories/search', 'CategoryController@ajaxSearch');

//book
Route::group(['prefix' => 'books'], function () {
    
});

Route::resource('books', 'BookController');