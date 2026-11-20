<?php

use App\Http\Controllers\AuthControlador;
use App\Http\Controllers\SitioControlador;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitaControlador;

Route::get('/', [SitioControlador::class, 'inicio'])->name('inicio');

Route::get('/registro', [AuthControlador::class, 'mostrarRegistro'])->name('registro');
Route::post('/registro', [AuthControlador::class, 'registrar'])->name('registro.guardar');

Route::get('/login', [AuthControlador::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthControlador::class, 'login'])->name('login.entrar');

Route::post('/logout', [AuthControlador::class, 'logout'])->name('logout');

Route::get('/citas/agendar', [CitaControlador::class, 'agendar'])->name('citas.agendar');
Route::post('/citas', [CitaControlador::class, 'confirmar'])->name('citas.confirmar');

Route::get('/citas', [CitaControlador::class, 'mis'])->name('citas.mis');                      // CUS07
Route::post('/citas/{id}/cancelar', [CitaControlador::class, 'cancelar'])->name('citas.cancelar'); // CUS06