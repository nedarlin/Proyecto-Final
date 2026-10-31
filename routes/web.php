<?php

use App\Http\Controllers\AuthControlador;
use App\Http\Controllers\SitioControlador;
use Illuminate\Support\Facades\Route;

Route::get('/', [SitioControlador::class, 'inicio'])->name('inicio');

Route::get('/registro', [AuthControlador::class, 'mostrarRegistro'])->name('registro');
Route::post('/registro', [AuthControlador::class, 'registrar'])->name('registro.guardar');

Route::get('/login', [AuthControlador::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthControlador::class, 'login'])->name('login.entrar');

Route::post('/logout', [AuthControlador::class, 'logout'])->name('logout');