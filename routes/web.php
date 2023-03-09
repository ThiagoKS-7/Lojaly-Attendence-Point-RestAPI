<?php

use App\Http\Controllers\AttendenceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
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

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->post('login', [AuthController::class, 'login']);
    $router->post('register', [AuthController::class, 'register']);
    $router->post('admin/register', [AuthController::class, 'registerAdmin']);
    $router->post('logout', [AuthController::class, 'logout']);
    $router->post('refresh', [AuthController::class, 'refresh']);
    $router->post('/attend', [AttendenceController::class, 'store']);
    $router->group(['prefix' => 'admin'], function () use ($router) {
        $router->get('/list-employees', [EmployeeController::class, 'index'])->middleware('role:admin');
        $router->post('/add-employee', [EmployeeController::class, 'store'])->middleware('role:admin');
        $router->put('/update-employee', [EmployeeController::class, 'update'])->middleware('role:admin');
        $router->delete('/delete-employee', [EmployeeController::class, 'delete'])->middleware('role:admin');
    });
});