<?php

namespace App\Http\Controllers;

use App\Models\AttendencePoint;
use Illuminate\Http\Request;

class AttendenceController extends Controller
{
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'user_id' => 'required|integer',
                'adm_id' => 'required|integer',
                'role' => 'required|string',
            ]);
            $funcionario =AttendencePoint::create([
                'employee_id' => $request['user_id'],
                'resp_adm_id' => $request['adm_id']
            ]);
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
