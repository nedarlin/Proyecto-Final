<?php

use App\Http\Controllers\AuthControlador;
use App\Http\Controllers\SitioControlador;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitaControlador;
use App\Http\Controllers\AgendaControlador;
use App\Http\Controllers\EvaluacionControlador;
use App\Http\Controllers\PagoControlador;

Route::get('/', [SitioControlador::class, 'inicio'])->name('inicio');

Route::get('/registro', [AuthControlador::class, 'mostrarRegistro'])->name('registro');
Route::post('/registro', [AuthControlador::class, 'registrar'])->name('registro.guardar');

Route::get('/login', [AuthControlador::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthControlador::class, 'login'])->name('login.entrar');

Route::post('/logout', [AuthControlador::class, 'logout'])->name('logout');

Route::get('/citas/agendar', [CitaControlador::class, 'agendar'])->name('citas.agendar');
Route::post('/citas', [CitaControlador::class, 'confirmar'])->name('citas.confirmar');

Route::get('/citas', [CitaControlador::class, 'mis'])->name('citas.mis');
Route::post('/citas/{id}/cancelar', [CitaControlador::class, 'cancelar'])->name('citas.cancelar');

Route::get('/agenda', [AgendaControlador::class, 'index'])->name('agenda');
Route::post('/agenda/{id}/avanzar', [AgendaControlador::class, 'avanzar'])->name('agenda.avanzar');

Route::get('/evaluaciones/crear/{id}', [EvaluacionControlador::class, 'crear'])->name('evaluaciones.crear');
Route::post('/evaluaciones/{id}', [EvaluacionControlador::class, 'guardar'])->name('evaluaciones.guardar');

Route::get('/pagos', [PagoControlador::class, 'index'])->name('pagos.index');
Route::get('/pagos/{id}', [PagoControlador::class, 'crear'])->name('pagos.crear');
Route::post('/pagos/{id}', [PagoControlador::class, 'guardar'])->name('pagos.guardar');
Route::get('/pagos/{id}/comprobante', [PagoControlador::class, 'comprobante'])->name('pagos.comprobante');

Route::get('/catalogo', [SitioControlador::class, 'catalogo'])->name('catalogo');
Route::get('/catalogo/{id}', [SitioControlador::class, 'servicio'])->name('catalogo.servicio');

