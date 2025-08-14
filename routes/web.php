<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/create-task', function () {
    return view('create-task');
})->name('create-task');

Route::get('/task-detail', function () {
    return view('task-detail');
})->name('task-detail');

Route::get('/reports', function () {
    return view('reports');
})->name('reports');
