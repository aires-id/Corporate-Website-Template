<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Controller extends BaseController
{
    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    protected function rememberOldInput(Request $request): void
    {
        $_SESSION['old'] = $request->except(['_token', 'password']);
    }

    protected function rememberErrors(array $errors): void
    {
        $_SESSION['form_errors'] = array_values($errors);
    }

    protected function adminUser(Request $request): User
    {
        $user = $request->attributes->get('admin_user');
        if (!$user instanceof User) {
            throw new AccessDeniedHttpException();
        }

        return $user;
    }

    protected function requireSystemAdmin(Request $request): User
    {
        $user = $this->adminUser($request);
        if ($user->role !== 'admin') {
            throw new AccessDeniedHttpException('Hanya administrator yang dapat mengelola akun.');
        }

        return $user;
    }
}
