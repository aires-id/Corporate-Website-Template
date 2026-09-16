<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class RequireAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $userId = $_SESSION['auth_user_id'] ?? null;
        $lastActivity = (int) ($_SESSION['admin_last_activity'] ?? 0);
        $lifetime = max(5, (int) env('SESSION_LIFETIME', 120)) * 60;

        if (!$userId || !$lastActivity || (time() - $lastActivity) > $lifetime) {
            $this->forgetAuthentication();
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Sesi admin berakhir. Silakan masuk kembali.'];

            return redirect('/admin/login');
        }

        $user = User::query()->find($userId);
        if (!$user || !$user->is_active || !in_array($user->role, ['admin', 'editor'], true)) {
            $this->forgetAuthentication();
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Akun tidak dapat mengakses halaman admin.'];

            return redirect('/admin/login');
        }

        $_SESSION['admin_last_activity'] = time();
        $request->attributes->set('admin_user', $user);

        return $next($request);
    }

    private function forgetAuthentication(): void
    {
        unset($_SESSION['auth_user_id'], $_SESSION['admin_last_activity']);
    }
}
