<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StartSecureSession
{
    public function handle(Request $request, Closure $next)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_httponly', '1');
            session_name('organization_portal_session');
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'secure' => $request->isSecure() || filter_var(env('SESSION_SECURE_COOKIE', false), FILTER_VALIDATE_BOOLEAN),
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $response = $next($request);

        if (!$response->isRedirection()) {
            unset($_SESSION['flash'], $_SESSION['form_errors'], $_SESSION['old']);
        }

        return $response;
    }
}
