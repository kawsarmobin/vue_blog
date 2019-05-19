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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {
    // Category
    Route::resource('categories', 'Admin\CategoriesController');
    Route::delete('/all-category-delete', 'Admin\CategoriesController@deleteAll')->name('category.delete.all');

    // Tag
    Route::resource('/tags', 'Admin\TagsController');
    Route::delete('/all-tag-delete', 'Admin\TagsController@deleteAll')->name('tag.delete.all');
});
