<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TenantOnly
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user()?->role === 'penghuni', 403, 'Halaman ini khusus penghuni kos.');

        return $next($request);
    }
}
