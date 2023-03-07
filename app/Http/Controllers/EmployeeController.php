<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $this->validate($request, [
                'name' => 'required',
                'age' => 'required',
                'office' => 'required',
                'resp_adm_id' => 'required'
            ]);
            $funcionario =Employee::create([
                'name' => $request['name'],
                'age' => $request['age'],
                'office' => $request['office'],
                'resp_adm_id' => $request['resp_adm_id']
            ]);
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


    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required',
            ]);
            $funcionario = Employee::find($request['id'])->update([
                'name' => $request['name'],
                'age' => $request['age'],
                'office' => $request['office'],
                'resp_adm_id' => $request['resp_adm_id']
            ]);
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


    public function delete(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required',
            ]);
            $this->validate($request, [
                'id' => 'required',
            ]);
            $funcionario = Employee::find($request['id'])->update([
                'name' => $request['name'],
                'age' => $request['age'],
                'office' => $request['office'],
                'resp_adm_id' => $request['resp_adm_id']
            ]);
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
}
