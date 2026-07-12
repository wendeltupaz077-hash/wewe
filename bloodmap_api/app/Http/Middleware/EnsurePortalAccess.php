<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePortalAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! in_array($user->role, ['super_admin', 'admin', 'facility_head', 'facility_staff'], true)) {
            return redirect()->route('portal.login');
        }

        return $next($request);
    }
}
