<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::controller('/produtos', ProdutosController::class);
Route::controller('/estoques', EstoqueController::class);
Route::controller('/meses', MesesController::class);



Route::get('/', function () {
    return view('home/index');
});



Route::auth();

Route::get('/home', 'HomeController@index');
