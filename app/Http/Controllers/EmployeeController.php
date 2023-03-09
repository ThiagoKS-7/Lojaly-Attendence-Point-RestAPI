<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        try {
            $funcionario = DB::select('SELECT * from employee');
            if(empty($funcionario)) {
                return response()->json($funcionario, 204);
            }
            return response()->json([
                'data' => $funcionario,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => $e->getMessage()
            ], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:200',
                'email' => 'required|string|email|max:200|unique:users',
                'age' => 'required|integer',
                'role' => 'required|string',
                'office' => 'required|string|max:200',
                'admin_id' => 'required|integer',
                'password' => 'required|string|min:8',
            ]);
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 'employee',
                'password' => Hash::make($request->password),
            ]);
            Employee::create([
                'name' => $request['name'],
                'age' => $request['age'],
                'user_id' => $user['id'],
                'office' => $request['office'],
                'resp_adm_id' => $request['admin_id'],
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Employee created successfully',
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => $e->getMessage()
            ], 400);
        }
    }


    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'func_id' => 'required|integer',
                'name' => 'required|string|max:200',
                'role' => 'required|string',
                'email' => 'required|string|email|max:200|unique:users',
                'age' => 'required|integer',
                'office' => 'required|string|max:200',
                'admin_id' => 'required|integer',
            ]);
            Employee::where(['id' => $request['func_id']])->update([
                'name' => $request['name'],
                'age' => $request['age'],
                'office' => $request['office'],
                'resp_adm_id' => $request['admin_id']
            ]);
            $funcionario = Employee::find($request['func_id']);
            if(empty($funcionario)) {
                return response()->json($funcionario, 204);
            } else {
                User::where(['id' => $funcionario->user_id])->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'role' => 'employee',
                ]);
                return response()->json([
                    'data' => $funcionario,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => $e->getMessage()
            ], 400);
        }
    }


    public function delete(Request $request)
    {
        try {
            $this->validate($request, [
                'func_id' => 'required|integer',
                'role' => 'required|string',
            ]);
            
            $func = Employee::find($request['func_id']);
            User::find($func['user_id'])->delete();
            $func->delete();
            return response()->json([
                'mensagem' => 'Funcionário deletado!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => $e->getMessage()
            ], 400);
        }
    }
}
