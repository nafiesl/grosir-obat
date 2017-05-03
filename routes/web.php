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
    return redirect()->route('login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    /**
     * Pages Routes
     */
    Route::get('/home', 'CartController@index')->name('home');

    /**
     * Cart / Trasanction Draft Routes
     */
    Route::get('drafts', 'CartController@index')->name('cart.index');
    Route::get('drafts/{draftKey}', 'CartController@show')->name('cart.show');
    Route::post('drafts/{draftKey}', 'CartController@store')->name('cart.store');
    Route::post('cart/add-draft', 'CartController@add')->name('cart.add');
    Route::post('cart/add-draft-item/{draftKey}/{product}', 'CartController@addDraftItem')->name('cart.add-draft-item');
    Route::patch('cart/update-draft-item/{draftKey}', 'CartController@updateDraftItem')->name('cart.update-draft-item');
    Route::patch('cart/{draftKey}/proccess', 'CartController@proccess')->name('cart.draft-proccess');
    Route::delete('cart/remove-draft-item/{draftKey}', 'CartController@removeDraftItem')->name('cart.remove-draft-item');
    Route::delete('cart/empty/{draftKey}', 'CartController@empty')->name('cart.empty');
    Route::delete('cart/remove', 'CartController@remove')->name('cart.remove');
    Route::delete('cart/destroy', 'CartController@destroy')->name('cart.destroy');

    /**
     * Products Routes
     */
    Route::resource('products', 'ProductsController', ['except' => ['create','show','edit']]);

    /**
     * Units Routes
     */
    Route::resource('units', 'UnitsController', ['except' => ['create','show','edit']]);

    /**
     * Backup Restore Database Routes
     */
    Route::post('backups/upload', ['as'=>'backups.upload', 'uses'=>'BackupsController@upload']);
    Route::post('backups/{fileName}/restore', ['as'=>'backups.restore', 'uses'=>'BackupsController@restore']);
    Route::get('backups/{fileName}/dl', ['as'=>'backups.download', 'uses'=>'BackupsController@download']);
    Route::resource('backups','BackupsController', ['except' => ['create', 'show', 'edit']]);
});
