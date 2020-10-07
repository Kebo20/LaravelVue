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

use App\Articulo;
use App\Categoria;







//INVITADOS
Route::group(['middleware' => ['guest']], function () {

    //LOGIN
    Route::get('/login', 'Auth\LoginController@showLoginForm');
    Route::get('/', 'Auth\LoginController@showLoginForm');

    Route::post('/login', 'Auth\LoginController@login')->name('login');
});


//USUARIOS LOGUEADOS
Route::group(['middleware' => ['auth']], function () {

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');


   

    Route::group(['middleware' => ['Almacenero']], function () {

        //CATEGORIA
        Route::get('/categoria', 'CategoriaController@index');
        Route::get('/categoria/listar', 'CategoriaController@listar');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');

        //ARTICULO
        Route::get('/articulo', 'ArticuloController@index');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');

        //PERSONA-PROVEEDOR
        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
    });

    Route::group(['middleware' => ['Vendedor']], function () {

        //PERSONA-CLIENTE
        Route::get('/persona', 'PersonaController@index');
        Route::post('/persona/registrar', 'PersonaController@store');
        Route::put('/persona/actualizar', 'PersonaController@update');
    });

    Route::group(['middleware' => ['Administrador']], function () {

        //ROL
        Route::get('/rol', 'RolController@index');

        //USER
        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');

        Route::get('/listar/{id}', 'ArticuloController@all');
        Route::get('/actualizar/{id}', 'ArticuloController@actualizar');
        Route::get('/crear', 'ArticuloController@crear');
        Route::get('/articulo/{id}', function ($id) {
            return Categoria::find($id)->articulos;
        });
    });

    
});


//Route::get('/home', 'HomeController@index')->name('home');


//Auth::routes();
