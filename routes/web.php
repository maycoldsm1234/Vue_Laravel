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

Route::get('/main   ', function () {
    return view('contenido.contenido');
})->name('main');

// Rutas Categoria
Route::get('/categoria','CategoriaController@index');
Route::post('/categoria/registrar','CategoriaController@store');
Route::put('/categoria/actualizar','CategoriaController@update');
Route::put('/categoria/desactivar','CategoriaController@desactivar');
Route::put('/categoria/activar','CategoriaController@activar');
Route::get('/categoria/selectCategoria','CategoriaController@selectCategoria');


// Rutas Articulo
Route::get('/articulo','ArticuloController@index');
Route::post('/articulo/registrar','ArticuloController@store');
Route::put('/articulo/actualizar','ArticuloController@update');
Route::put('/articulo/desactivar','ArticuloController@desactivar');
Route::put('/articulo/activar','ArticuloController@activar');


// Rutas Cliente
Route::get('/cliente','ClienteController@index');
Route::post('/cliente/registrar','ClienteController@store');
Route::put('/cliente/actualizar','ClienteController@update');


// Rutas Proveedor
Route::get('/proveedor','ProveedorController@index');
Route::post('/proveedor/registrar','ProveedorController@store');
Route::put('/proveedor/actualizar','ProveedorController@update');


// Rutas Rol
Route::get('/rol','RolController@index');
Route::post('/rol/registrar','RolController@store');
Route::put('/rol/actualizar','RolController@update');

Route::get('/rol/selectRol', 'RolController@selectRol');

// Rutas Usuario
Route::get('/user','UserController@index');
Route::post('/user/registrar','UserController@store');
Route::put('/user/actualizar','UserController@update');
Route::put('/user/desactivar','UserController@desactivar');
Route::put('/user/activar','UserController@activar');


// Rutas Usuario
Route::get('/','Auth\LoginController@showLoginForm');

Route::post('/login','Auth\LoginController@login')->name('login');

Route::get('/home', 'HomeController@index')->name('home');
