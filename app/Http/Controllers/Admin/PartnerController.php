<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function __construct(private SettingsService $settings)
    {
    }

    public function index(Request $request)
    {
        return view('admin.partners.index', [
            'site' => $this->settings->all(),
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'user' => $this->adminUser($request),
            'partners' => Partner::query()->latest('cooperation_year')->latest()->paginate(10),
        ]);
    }

    public function create(Request $request)
    {
        return $this->form($request, new Partner(), 'Tambah Kerja Sama');
    }

    public function store(Request $request)
    {
        $this->adminUser($request);

        return $this->save($request, new Partner(), true);
    }

    public function edit(Request $request, int $id)
    {
        return $this->form($request, Partner::query()->findOrFail($id), 'Edit Kerja Sama');
    }

    public function update(Request $request, int $id)
    {
        $this->adminUser($request);

        return $this->save($request, Partner::query()->findOrFail($id), false);
    }

    public function destroy(Request $request, int $id)
    {
        $this->adminUser($request);
        Partner::query()->findOrFail($id)->delete();
        $this->flash('success', 'Data kerja sama telah dihapus.');

        return redirect('/admin/partners');
    }

    private function form(Request $request, Partner $partner, string $title)
    {
        return view('admin.partners.form', [
            'site' => $this->settings->all(),
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'user' => $this->adminUser($request),
            'partner' => $partner,
            'pageTitle' => $title,
        ]);
    }

    private function save(Request $request, Partner $partner, bool $isNew)
    {
        $validator = app('validator')->make($request->all(), [
            'name' => ['required', 'string', 'max:191'],
            'logo_url' => ['nullable', 'url:https', 'max:2048'],
            'description' => ['nullable', 'string', 'max:5000'],
            'website_url' => ['nullable', 'url:https', 'max:2048'],
            'cooperation_year' => ['nullable', 'integer', 'between:2000,2100'],
        ]);

        if ($validator->fails()) {
            $this->rememberOldInput($request);
            $this->rememberErrors($validator->errors()->all());
            $this->flash('error', 'Data kerja sama belum dapat disimpan.');

            return redirect($isNew ? '/admin/partners/create' : '/admin/partners/' . $partner->id . '/edit');
        }

        $partner->fill($validator->validated())->save();
        $this->flash('success', $isNew ? 'Kerja sama berhasil ditambahkan.' : 'Kerja sama berhasil diperbarui.');

        return redirect('/admin/partners/' . $partner->id . '/edit');
    }
}
