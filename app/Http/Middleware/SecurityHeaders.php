<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $adsClient = trim((string) env('GOOGLE_ADS_CLIENT_ID', ''));
        $scriptSources = "'self'";
        $frameSources = "'self'";

        if ($adsClient !== '') {
            $scriptSources .= ' https://pagead2.googlesyndication.com https://*.googlesyndication.com';
            $frameSources .= ' https://*.googlesyndication.com https://*.doubleclick.net';
        }

        $response->headers->set('Content-Security-Policy', implode('; ', [
            "default-src 'self'",
            "script-src {$scriptSources}",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data: https:",
            "font-src 'self' data:",
            "connect-src 'self'",
            "frame-src {$frameSources}",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ]));
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}
