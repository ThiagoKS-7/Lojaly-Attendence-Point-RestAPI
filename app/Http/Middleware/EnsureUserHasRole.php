<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

use function PHPUnit\Framework\isEmpty;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = User::where(['id'=>$request['user_id']])->get()->first();
        if (strlen($user['remember_token']) <= 0) {
            return response()->json([
                'mensagem' => "Erro! Uusario nao esta logado."
            ], 401);
        }
        else if (  ($user['role'] !== $role)) {
            return    response()->json([
                'mensagem' => "Erro! Permissao insuficiente, somente o admin pode ter acesso."
            ], 403);
        } 
        return $next($request);
    }
 
}