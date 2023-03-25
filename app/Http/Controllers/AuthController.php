<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Adm;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Emp;

class AuthController extends Controller
{


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $login = User::where(['email'=> $request['email']])->get()->first();
        if (Hash::check($request['password'],$login['password'])) {
            $newToken = $login->createToken('MyApp')->plainTextToken;
            User::where(['id' => $login['id']])->update(['remember_token'=> $newToken]);
            $emp = Employee::where(['user_id' => $login['id']])->get()->first();
            $adm = Admin::where(['id' => $emp["resp_adm_id"]])->get()->first();
            Auth::login($login);
            $user = Auth::user();
            $user["adm_id"] =  $emp["resp_adm_id"];
            $user["emp_id"] =  $emp["id"];
            $user["adm_name"] = $adm["name"];
            return response()->json([
                    'status' => 'success',
                    'user' => $user,
                    'auth' => [
                        'token' => $newToken,
                        'type' => 'bearer',
                    ]
                ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);


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
                $token = $user->createToken('MyApp')->plainTextToken;
                User::where(['id' => $user['id']])->update(['remember_token'=> $token]);
                Employee::create([
                    'name' => $request['name'],
                    'age' => $request['age'],
                    'user_id' => $user['id'],
                    'office' => $request['office'],
                    'resp_adm_id' => $request['admin_id'],
                ]);
                $emp = Employee::where(['user_id' => $user['id']])->get()->first();
                $adm = Admin::where(['id' => $request['admin_id']])->get()->first();
                Auth::login($user);
                $user = Auth::user(); 
                $user["adm_id"] = $request['admin_id'];
                $user["emp_id"] = $user["id"];
                $user["adm_name"] = $adm["name"];
                return response()->json([
                    'status' => 'success',
                    'message' => 'Employee created successfully',
                    'user' => $user,
                    'auth'=> [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
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
                $token = $user->createToken('MyApp')->plainTextToken;
                User::where(['id' => $user['id']])->update(['remember_token'=> $token]);
                Admin::create([
                    'name' => $request['name'],
                    'age' => $request['age'],
                    'user_id' => $user['id'],
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Admin created successfully',
                    'user' => $user,
                    'auth'=> [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => $e->getMessage()
            ], 400);
        }
    }

    public function getUserById($id) {
        try {
            $user = User::find($id);
            if ($user['role'] == 'admin') {

                return Adm::select([
                    'adm.user_id as id',
                    'usr.role',
                    'adm.age',
                    'adm.name',
                    'usr.email',
                    'usr.remember_token as token'
                ])
                ->leftJoin('users as usr', 'usr.id', '=', 'adm.user_id')
                ->where('usr.id', intval($id))->get()->first();
            }
            else if ($user['role'] == 'employee') {

                return Emp::select([
                    'emp.user_id as id',
                    'adm.id as adm_id',
                    'usr.role',
                    'emp.age',
                    'adm.name as admin_name',
                    'emp.office',
                    'emp.name',
                    'usr.email',
                    'usr.remember_token as token'
                ])
                ->leftJoin('users as usr', 'usr.id', '=', 'emp.user_id')
                ->leftJoin('admin as adm', 'adm.id', '=', 'emp.resp_adm_id')
                ->where('usr.id', intval($id))->get()->first();
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => $e->getMessage()
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
            ]);
            if ($request) {
                User::where(['id' => $request['user_id']])->update(['remember_token'=> ""]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Successfully logged out',
                ]);
            }
        }
        catch (\Exception $e) {
            return response()->json([
                'mensagem' => $e->getMessage()
            ], 400);
        }
    }
}