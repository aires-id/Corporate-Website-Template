<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private SettingsService $settings)
    {
    }

    public function index(Request $request)
    {
        $user = $this->requireSystemAdmin($request);
        $query = User::query()->latest();
        $keyword = trim((string) $request->query('q', ''));
        $role = (string) $request->query('role', '');

        if ($keyword !== '') {
            $query->where(function ($builder) use ($keyword) {
                $builder->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }
        if (in_array($role, ['admin', 'editor'], true)) {
            $query->where('role', $role);
        }

        return view('admin.users.index', [
            'site' => $this->settings->all(),
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'user' => $user,
            'accounts' => $query->paginate(10)->appends($request->query()),
            'filters' => ['q' => $keyword, 'role' => $role],
        ]);
    }

    public function update(Request $request, int $id)
    {
        $actor = $this->requireSystemAdmin($request);
        $account = User::query()->findOrFail($id);
        $validator = app('validator')->make($request->all(), [
            'role' => ['required', 'in:admin,editor'],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            $this->flash('error', 'Perubahan akun tidak valid.');

            return redirect('/admin/users');
        }

        $data = $validator->validated();
        if ($actor->id === $account->id && !(bool) $data['is_active']) {
            $this->flash('error', 'Anda tidak dapat menonaktifkan akun sendiri.');

            return redirect('/admin/users');
        }

        $account->fill([
            'role' => $data['role'],
            'is_active' => (bool) $data['is_active'],
        ])->save();
        $this->flash('success', 'Status akun berhasil diperbarui.');

        return redirect('/admin/users');
    }
}
