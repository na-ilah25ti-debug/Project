<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OwnerOnly
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user()?->role === 'pemilik', 403, 'Halaman ini khusus pemilik kos.');

        return $next($request);
    }
}
