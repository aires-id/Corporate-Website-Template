<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ContactMessage;
use App\Models\FinancialReport;
use App\Models\Partner;
use App\Models\Project;
use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Throwable;

class DashboardController extends Controller
{
    public function __construct(private SettingsService $settings)
    {
    }

    public function index(Request $request)
    {
        $this->adminUser($request);
        $stats = [
            'accounts' => $this->count(User::class),
            'articles' => $this->count(Article::class),
            'published' => $this->count(Article::class, ['status' => 'published']),
            'drafts' => $this->count(Article::class, ['status' => 'draft']),
            'reports' => $this->count(FinancialReport::class),
            'projects' => $this->count(Project::class),
            'partners' => $this->count(Partner::class),
            'messages' => $this->count(ContactMessage::class),
            'article_views' => $this->sum(Article::class, 'views_count'),
        ];

        return view('admin.dashboard', [
            'site' => $this->settings->all(),
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'user' => $this->adminUser($request),
            'stats' => $stats,
        ]);
    }

    private function count(string $model, array $where = []): int
    {
        try {
            return $model::query()->where($where)->count();
        } catch (Throwable) {
            return 0;
        }
    }

    private function sum(string $model, string $column): int
    {
        try {
            return (int) $model::query()->sum($column);
        } catch (Throwable) {
            return 0;
        }
    }
}
