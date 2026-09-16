<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancialReport;
use App\Services\FileUploadService;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use RuntimeException;

class ReportController extends Controller
{
    public function __construct(private FileUploadService $uploads, private SettingsService $settings)
    {
    }

    public function index(Request $request)
    {
        return view('admin.reports.index', [
            'site' => $this->settings->all(),
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'user' => $this->adminUser($request),
            'reports' => FinancialReport::query()->latest('year')->latest('month')->paginate(10),
        ]);
    }

    public function create(Request $request)
    {
        return $this->form($request, new FinancialReport(), 'Tambah Laporan Keuangan');
    }

    public function store(Request $request)
    {
        $this->adminUser($request);

        return $this->save($request, new FinancialReport(), true);
    }

    public function edit(Request $request, int $id)
    {
        return $this->form($request, FinancialReport::query()->findOrFail($id), 'Edit Laporan Keuangan');
    }

    public function update(Request $request, int $id)
    {
        $this->adminUser($request);

        return $this->save($request, FinancialReport::query()->findOrFail($id), false);
    }

    public function destroy(Request $request, int $id)
    {
        $this->adminUser($request);
        $report = FinancialReport::query()->findOrFail($id);
        $path = $report->absolutePath();
        $report->delete();
        if (is_file($path)) {
            @unlink($path);
        }
        $this->flash('success', 'Laporan telah dihapus.');

        return redirect('/admin/reports');
    }

    private function form(Request $request, FinancialReport $report, string $title)
    {
        return view('admin.reports.form', [
            'site' => $this->settings->all(),
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'user' => $this->adminUser($request),
            'report' => $report,
            'pageTitle' => $title,
        ]);
    }

    private function save(Request $request, FinancialReport $report, bool $isNew)
    {
        $maxKilobytes = max(1, (int) floor(((int) env('MAX_PDF_SIZE', 10485760)) / 1024));
        $rules = [
            'title' => ['required', 'string', 'max:191'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'between:2000,2100'],
            'description' => ['nullable', 'string', 'max:5000'],
            'pdf' => [$isNew ? 'required' : 'nullable', 'file', 'max:' . $maxKilobytes],
        ];
        $validator = app('validator')->make($request->all(), $rules);

        if ($validator->fails()) {
            $this->rememberOldInput($request);
            $this->rememberErrors($validator->errors()->all());
            $this->flash('error', 'Laporan belum dapat disimpan.');

            return redirect($isNew ? '/admin/reports/create' : '/admin/reports/' . $report->id . '/edit');
        }

        $data = $validator->validated();
        $oldPath = $report->stored_path ? $report->absolutePath() : null;
        try {
            if ($request->hasFile('pdf')) {
                $report->fill($this->uploads->storePdf($request->file('pdf')));
            }
        } catch (RuntimeException $exception) {
            $this->flash('error', $exception->getMessage());

            return redirect($isNew ? '/admin/reports/create' : '/admin/reports/' . $report->id . '/edit');
        }

        $report->fill([
            'title' => trim($data['title']),
            'month' => (int) $data['month'],
            'year' => (int) $data['year'],
            'description' => trim((string) ($data['description'] ?? '')),
            'uploaded_by' => $this->adminUser($request)->id,
            'uploaded_at' => Carbon::now(),
        ])->save();

        if ($oldPath && $oldPath !== $report->absolutePath() && is_file($oldPath)) {
            @unlink($oldPath);
        }
        $this->flash('success', $isNew ? 'Laporan berhasil diunggah.' : 'Laporan berhasil diperbarui.');

        return redirect('/admin/reports/' . $report->id . '/edit');
    }
}
