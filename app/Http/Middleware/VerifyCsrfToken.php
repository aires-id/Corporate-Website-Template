<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerifyCsrfToken
{
    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            $token = (string) ($request->input('_token') ?: $request->header('X-CSRF-TOKEN'));
            $sessionToken = (string) ($_SESSION['csrf_token'] ?? '');

            if ($sessionToken === '' || !hash_equals($sessionToken, $token)) {
                throw new HttpException(419, 'Sesi keamanan sudah habis. Silakan muat ulang halaman dan coba lagi.');
            }
        }

        return $next($request);
    }
}
