<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(private SettingsService $settings)
    {
    }

    public function index(Request $request)
    {
        return view('admin.projects.index', [
            'site' => $this->settings->all(),
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'user' => $this->adminUser($request),
            'projects' => Project::query()->latest('year')->latest()->paginate(10),
        ]);
    }

    public function create(Request $request)
    {
        return $this->form($request, new Project(), 'Tambah Project');
    }

    public function store(Request $request)
    {
        $this->adminUser($request);

        return $this->save($request, new Project(), true);
    }

    public function edit(Request $request, int $id)
    {
        return $this->form($request, Project::query()->findOrFail($id), 'Edit Project');
    }

    public function update(Request $request, int $id)
    {
        $this->adminUser($request);

        return $this->save($request, Project::query()->findOrFail($id), false);
    }

    public function destroy(Request $request, int $id)
    {
        $this->adminUser($request);
        Project::query()->findOrFail($id)->delete();
        $this->flash('success', 'Project telah dihapus.');

        return redirect('/admin/projects');
    }

    private function form(Request $request, Project $project, string $title)
    {
        return view('admin.projects.form', [
            'site' => $this->settings->all(),
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'user' => $this->adminUser($request),
            'project' => $project,
            'pageTitle' => $title,
        ]);
    }

    private function save(Request $request, Project $project, bool $isNew)
    {
        $validator = app('validator')->make($request->all(), [
            'name' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string', 'max:5000'],
            'image_url' => ['nullable', 'url:https', 'max:2048'],
            'status' => ['required', 'in:planned,ongoing,completed'],
            'year' => ['nullable', 'integer', 'between:2000,2100'],
            'related_url' => ['nullable', 'url:https', 'max:2048'],
        ]);

        if ($validator->fails()) {
            $this->rememberOldInput($request);
            $this->rememberErrors($validator->errors()->all());
            $this->flash('error', 'Project belum dapat disimpan.');

            return redirect($isNew ? '/admin/projects/create' : '/admin/projects/' . $project->id . '/edit');
        }

        $project->fill($validator->validated())->save();
        $this->flash('success', $isNew ? 'Project berhasil ditambahkan.' : 'Project berhasil diperbarui.');

        return redirect('/admin/projects/' . $project->id . '/edit');
    }
}
