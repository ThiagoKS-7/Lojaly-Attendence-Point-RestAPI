<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Employee;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register', 'registerAdmin']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }

    public function register(Request $request){
        try {
            $request->validate([
                'name' => 'required|string|max:200',
                'email' => 'required|string|email|max:200|unique:users',
                'age' => 'required|integer',
                'office' => 'required|string|max:200',
                'admin_id' => 'required|integer',
                'role'=> 'required|in:admin,employee',
                'password' => 'required|string|min:8',
            ]);
            if($request['role'] == "admin"){
                return response()->json([
                    'Mensagem' => 'Erro! Esse endpoint cadastra apenas funcionarios',
                ], 400);
            }
            else if(count(Admin::get()) <= 0) {
                return response()->json([
                    'Mensagem' => 'Erro! necessario haver ao menos um admin cadastrado',
                ], 404);
 
            } else if(!Admin::find($request['admin_id'])) {
                return response()->json([
                    'Mensagem' => 'Admin nao encontrado.',
                ], 404);
            } else if ($request['role'] == "employee") {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'role' => $request['role'],
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
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => $e->getMessage()
            ], 400);
        }
    }
    public function registerAdmin(Request $request){
        try {
            $request->validate([
                'name' => 'required|string|max:200',
                'email' => 'required|string|email|max:200|unique:users',
                'age' => 'required|integer',
                'role'=> 'required|in:admin,employee',
                'password' => 'required|string|min:8',
            ]);
            if ($request['role'] =='admin') {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                Admin::create([
                    'name' => $request['name'],
                    'age' => $request['age'],
                    'user_id' => $user['id'],
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Admin created successfully',
                    'user' => $user,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => $e->getMessage()
            ], 400);
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}