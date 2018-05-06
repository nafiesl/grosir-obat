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

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Change Password Routes...
Route::get('change-password', 'Auth\ChangePasswordController@getChangePassword')->name('change-password');
Route::post('change-password', 'Auth\ChangePasswordController@postChangePassword')->name('change-password');

Route::group(['middleware' => 'auth'], function () {
    /*
     * Pages Routes
     */
    Route::get('/', 'CartController@index')->name('home');

    /*
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

    /*
     * Products Routes
     */
    Route::get('products/price-list', ['as' => 'products.price-list', 'uses' => 'ProductsController@priceList']);
    Route::resource('products', 'ProductsController', ['except' => ['create', 'show', 'edit']]);

    /*
     * Units Routes
     */
    Route::resource('units', 'UnitsController', ['except' => ['create', 'show', 'edit']]);

    /*
     * Users Routes
     */
    Route::resource('users', 'UsersController', ['except' => ['create', 'show', 'edit']]);

    /*
     * Transactions Routes
     */
    Route::get('transactions', ['as' => 'transactions.index', 'uses' => 'TransactionsController@index']);
    Route::get('transactions/{transaction}', ['as' => 'transactions.show', 'uses' => 'TransactionsController@show']);
    Route::get('transactions/{transaction}/pdf', ['as' => 'transactions.pdf', 'uses' => 'TransactionsController@pdf']);
    Route::get('transactions/{transaction}/receipt', ['as' => 'transactions.receipt', 'uses' => 'TransactionsController@receipt']);

    /*
     * Reports Routes
     */
    Route::group(['prefix' => 'reports'], function () {

        /*
         * Sales Routes
         */
        Route::get('sales', ['as' => 'reports.sales.index', 'uses' => 'Reports\SalesController@monthly']);
        Route::get('sales/daily', ['as' => 'reports.sales.daily', 'uses' => 'Reports\SalesController@daily']);
        Route::get('sales/monthly', ['as' => 'reports.sales.monthly', 'uses' => 'Reports\SalesController@monthly']);
        Route::get('sales/yearly', ['as' => 'reports.sales.yearly', 'uses' => 'Reports\SalesController@yearly']);
    });

    /*
     * Backup Restore Database Routes
     */
    Route::post('backups/upload', ['as' => 'backups.upload', 'uses' => 'BackupsController@upload']);
    Route::post('backups/{fileName}/restore', ['as' => 'backups.restore', 'uses' => 'BackupsController@restore']);
    Route::get('backups/{fileName}/dl', ['as' => 'backups.download', 'uses' => 'BackupsController@download']);
    Route::resource('backups', 'BackupsController', ['except' => ['create', 'show', 'edit']]);

    /*
     * Log Viewer routes
     */
    Route::get('log-files', ['as' => 'log-files.index', 'uses' => 'LogFilesController@index']);
    Route::get('log-files/{filename}', ['as' => 'log-files.show', 'uses' => 'LogFilesController@show']);
    Route::get('log-files/{filename}/download', ['as' => 'log-files.download', 'uses' => 'LogFilesController@download']);
});
