<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        
        return (
           ($request['role'] === $role) ? 
            $next($request) : 
            response()->json([
                'mensagem' => "Erro! Permissao insuficiente, somente o admin pode ter acesso."
            ], 405)
        );
    }
 
}