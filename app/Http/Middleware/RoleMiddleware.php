<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {

        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Ambil role user yang sedang login
        $userRole = auth()->user()->role;

        // Cek apakah role user ada di daftar role yang diizinkan
        if (!in_array($userRole, $roles)) {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        return $next($request);

    }
}
