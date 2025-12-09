<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Enums\UserRole;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // $request->user()->role is now an Enum Object due to casting in User model
        // We need to compare its backing value (string) with the route parameter
        if (! $request->user() || $request->user()->role->value !== $role) {
            
            if ($request->user()) {
                if ($request->user()->role === UserRole::ADMIN) {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('dashboard');
            }
            
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
