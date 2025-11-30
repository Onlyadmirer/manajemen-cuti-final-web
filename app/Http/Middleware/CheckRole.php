<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (! $request->user()) {
            return redirect('login');
        }

        // 2. Cek apakah role user ada di dalam daftar role yang diizinkan
        // Contoh pemakaian di route: 'role:admin,hr'
        if (! in_array($request->user()->role, $roles)) {
            // Jika tidak punya akses, lempar error 403 (Forbidden)
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk masuk ke halaman ini.');
        }

        return $next($request);
    }
}