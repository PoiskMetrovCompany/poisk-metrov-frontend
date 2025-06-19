<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminPanelAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::id()) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
