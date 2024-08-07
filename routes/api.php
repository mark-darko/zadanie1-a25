<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/employees', [EmployeeController::class, 'store']);
Route::post('/transactions', [EmployeeController::class, 'addTransaction']);
Route::post('/unpaid-salaries', [EmployeeController::class, 'unpaidSalaries']);
Route::post('/pay-all-salaries', [EmployeeController::class, 'payAllSalaries']);
