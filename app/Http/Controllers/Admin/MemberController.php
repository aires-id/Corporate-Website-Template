<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationMember;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct(private SettingsService $settings)
    {
    }

    public function index(Request $request)
    {
        return view('admin.members.index', [
            'site' => $this->settings->all(),
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'user' => $this->adminUser($request),
            'members' => OrganizationMember::query()->orderBy('sort_order')->paginate(10),
        ]);
    }

    public function create(Request $request)
    {
        return $this->form($request, new OrganizationMember(), 'Tambah Anggota Struktur');
    }

    public function store(Request $request)
    {
        $this->adminUser($request);

        return $this->save($request, new OrganizationMember(), true);
    }

    public function edit(Request $request, int $id)
    {
        return $this->form($request, OrganizationMember::query()->findOrFail($id), 'Edit Anggota Struktur');
    }

    public function update(Request $request, int $id)
    {
        $this->adminUser($request);

        return $this->save($request, OrganizationMember::query()->findOrFail($id), false);
    }

    public function destroy(Request $request, int $id)
    {
        $this->adminUser($request);
        OrganizationMember::query()->findOrFail($id)->delete();
        $this->flash('success', 'Anggota struktur dihapus.');

        return redirect('/admin/members');
    }

    private function form(Request $request, OrganizationMember $member, string $title)
    {
        return view('admin.members.form', [
            'site' => $this->settings->all(),
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'user' => $this->adminUser($request),
            'member' => $member,
            'pageTitle' => $title,
        ]);
    }

    private function save(Request $request, OrganizationMember $member, bool $isNew)
    {
        $validator = app('validator')->make($request->all(), [
            'name' => ['required', 'string', 'max:150'],
            'position' => ['required', 'string', 'max:150'],
            'photo_url' => ['nullable', 'url:https', 'max:2048'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            $this->rememberOldInput($request);
            $this->rememberErrors($validator->errors()->all());
            $this->flash('error', 'Data anggota belum dapat disimpan.');

            return redirect($isNew ? '/admin/members/create' : '/admin/members/' . $member->id . '/edit');
        }

        $data = $validator->validated();
        $member->fill([
            'name' => trim($data['name']),
            'position' => trim($data['position']),
            'photo_url' => ($data['photo_url'] ?? null) ?: null,
            'short_description' => trim((string) ($data['short_description'] ?? '')),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ])->save();
        $this->flash('success', $isNew ? 'Anggota struktur ditambahkan.' : 'Data anggota struktur diperbarui.');

        return redirect('/admin/members/' . $member->id . '/edit');
    }
}
