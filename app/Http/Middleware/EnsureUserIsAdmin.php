<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get admin email from environment variable or use default
        $adminEmail = config('app.admin_email', 'shaymaazaki88@gmail.com');

        // Check if user is authenticated and is the admin
        if (!$request->user() || $request->user()->email !== $adminEmail) {
            abort(403, 'Unauthorized access. Only the administrator can access this area.');
        }

        return $next($request);
    }
}
