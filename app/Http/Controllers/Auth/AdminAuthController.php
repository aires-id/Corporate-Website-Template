<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RequestRateLimiter;
use App\Services\SeoService;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminAuthController extends Controller
{
    public function __construct(
        private SettingsService $settings,
        private SeoService $seo,
        private RequestRateLimiter $limiter,
    ) {
    }

    public function showLogin()
    {
        if (!empty($_SESSION['auth_user_id'])) {
            return redirect('/admin/dashboard');
        }

        $site = $this->settings->all();

        return view('admin.login', [
            'site' => $site,
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'seo' => $this->seo->page($site, 'Masuk Admin', 'Akses terbatas untuk pengelolaan website.', '/admin/login', 'noindex, nofollow'),
        ]);
    }

    public function login(Request $request)
    {
        $validator = app('validator')->make($request->all(), [
            'email' => ['required', 'email:rfc', 'max:191'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            $this->rememberOldInput($request);
            $this->rememberErrors($validator->errors()->all());
            $this->flash('error', 'Mohon isi email dan kata sandi dengan benar.');

            return redirect('/admin/login');
        }

        $limit = max(1, (int) env('LOGIN_RATE_LIMIT', 5));
        if ($this->limiter->tooMany('admin-login', $request, $limit, true)) {
            $this->flash('error', 'Terlalu banyak percobaan masuk. Silakan coba lagi beberapa menit lagi.');

            return redirect('/admin/login');
        }

        $credentials = $validator->validated();
        $user = User::query()->where('email', strtolower($credentials['email']))->first();
        if (!$user || !$user->is_active || !password_verify($credentials['password'], $user->password)) {
            $this->limiter->hit('admin-login', $request);
            $this->flash('error', 'Email atau kata sandi tidak sesuai.');

            return redirect('/admin/login');
        }

        session_regenerate_id(true);
        $_SESSION['auth_user_id'] = $user->id;
        $_SESSION['admin_last_activity'] = time();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $user->forceFill(['last_login_at' => Carbon::now()])->save();
        $this->limiter->clear('admin-login', $request);
        $this->flash('success', 'Anda berhasil masuk ke area admin.');

        return redirect('/admin/dashboard');
    }

    public function logout()
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'] ?: '/', $params['domain'] ?? '', (bool) ($params['secure'] ?? false), (bool) ($params['httponly'] ?? true));
        }
        session_destroy();

        return redirect('/admin/login');
    }
}
