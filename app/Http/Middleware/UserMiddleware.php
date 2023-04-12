<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if ($user->is_admin) {
            return $next($request);
        }

        switch (true) {
            case request()->route('user'):
                $user_id = request()->route('user')->id;
                break;
            case request()->route('user_profile'):
                $user_id = request()->route('user_profile')->user_id;
                break;
            case request()->route('user_weekly_attachment'):
                $user_id = request()->route('user_weekly_attachment')->user_id;
                break;
                
            default:
                $user_id = null;
                break;
        }
        
        if ($user_id && $user->id !== $user_id) { // Check user ownership
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return $next($request);
    }
}
