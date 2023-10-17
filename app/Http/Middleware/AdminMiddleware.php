<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request; // Import the correct Request class

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is an admin
        if (auth()->check() && auth()->user()->role_id=='1') {
            return $next($request);
        }

        return abort(403, 'Unauthorized');
    }
}
