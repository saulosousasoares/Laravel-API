<?php

use App\Constants\RouteConstants;

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

Route::get(RouteConstants::CONTATO_ID, 'ContatoController@getById');

Route::get(RouteConstants::CONTATO_NOME, 'ContatoController@getByNome');

Route::get(RouteConstants::CONTATO_EMAIL, 'ContatoController@getByEmail');

Route::get(RouteConstants::CONTATOS, 'ContatoController@listAll');

Route::post(RouteConstants::CONTATO_INSERT, 'ContatoController@insertContato');

Route::put(RouteConstants::CONTATO_UPDATE, 'ContatoController@updateContato');

Route::delete(RouteConstants::CONTATO_DELETE, 'ContatoController@deleteContato');
