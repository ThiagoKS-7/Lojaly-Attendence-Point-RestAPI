<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->get('/', function () {
        return view('welcome');
    });
    $router->get('/token', function() {
        return csrf_token();
    });
    $router->post('login', [AuthController::class, 'login']);
    $router->post('register', [AuthController::class, 'register']);
    $router->post('admin/register', [AuthController::class, 'registerAdmin']);
    $router->post('logout', [AuthController::class, 'logout']);
    $router->post('refresh', [AuthController::class, 'refresh']);
    $router->post('/attend', [EmployeeController::class, 'store'])->middleware(['auth']);
    $router->group(['prefix' => 'admin'], function () use ($router) {
        $router->get('/list-employees', [EmployeeController::class, 'index'])->middleware('role:admin');
        $router->post('/add-employee', [EmployeeController::class, 'store'])->middleware('role:admin');
        $router->put('/update-employee', [EmployeeController::class, 'update'])->middleware('role:admin');
        $router->delete('/delete-employee', [EmployeeController::class, 'delete'])->middleware('role:admin');
    });
});