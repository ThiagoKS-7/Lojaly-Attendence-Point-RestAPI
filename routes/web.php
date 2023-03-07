<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
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
     $router->get('/', function () {
        return view('welcome');
    });
    $router->group(['prefix' => 'employee'], function () use ($router) {
        $router->get('/', [EmployeeController::class, 'index']);
        $router->post('/add', [EmployeeController::class, 'store']);
        $router->patch('/', [EmployeeController::class, 'update']);
        $router->put('/', [EmployeeController::class, 'updateAll']);
        $router->delete('/', [EmployeeController::class, 'delete']);
    });
});