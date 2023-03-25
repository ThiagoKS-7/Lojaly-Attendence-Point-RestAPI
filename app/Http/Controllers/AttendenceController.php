<?php

namespace App\Http\Controllers;

use App\Models\AttendencePoint;
use App\Models\AttPoint;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Admin;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

#TODO: STORE, INDEX, UPDATE, DELETE
class AttendenceController extends Controller
{
    public function list() {
        try {
            return AttPoint::select([
                'ap.id',
                'e.name',
                'e.office',
                'e.age',
                'ad.name as admin_name',
                'ad.created_at'
            ])
            ->leftJoin('employee as e', 'e.id', '=', 'ap.employee_id')
            ->leftJoin('admin as ad', 'ad.id', '=', 'ap.resp_adm_id')->get();
        }  catch (\Exception $e) {
            return response()->json([
                'mensagem' => $e->getMessage()
            ], 400);
        }
    }

    public function recents($id) {
        return AttendencePoint::select("*")
        ->where('employee_id', $id)
        ->limit(8)
        ->get();
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'func_id' => 'required|integer',
                'adm_id' => 'required|integer',
                'user_id'=> 'required|integer',
            ]);

            $user = User::where(['id'=>$request['user_id']])->get()->first();
            $func = Employee::where(['id'=>$request['func_id']])->get()->first();
            $adm = Admin::where(['id'=>$request['adm_id']])->get()->first();
            
            [$key, $notFoundLabels] = Arr::divide(Arr::except([
                empty($user)=>"Usuario",
                empty($func)=>"Funcionario",
                empty($adm)=>"Admin",
            ], [false]));

            if (count($notFoundLabels) > 0) {
                return response()->json([
                    'mensagem' => "Erro! ". $notFoundLabels[0]." nao econtrado!."
                ], 401);
            }
            else if (strlen($user['remember_token']) <= 0) {
                return response()->json([
                    'mensagem' => "Erro! Usuario nao esta logado."
                ], 401);
            } else {
                $funcionario =AttendencePoint::create([
                    'employee_id' => $request['func_id'],
                    'resp_adm_id' => $request['adm_id']
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
}
