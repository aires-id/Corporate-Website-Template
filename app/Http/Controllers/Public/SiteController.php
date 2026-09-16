<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\FinancialReport;
use App\Models\OrganizationMember;
use App\Models\Partner;
use App\Models\Project;
use App\Services\ArticleViewService;
use App\Services\SeoService;
use App\Services\SettingsService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Throwable;

class SiteController extends Controller
{
    public function __construct(
        private SettingsService $settings,
        private SeoService $seo,
        private ArticleViewService $articleViews,
    ) {
    }

    public function home()
    {
        $site = $this->settings->all();
        $articles = $this->safeCollection(fn () => Article::published()->with('author')->latest('published_at')->take(3)->get());
        $projects = $this->safeCollection(fn () => Project::query()->latest()->take(3)->get());

        return $this->render('public.corporate-home', $site, $this->seo->page(
            $site,
            $site['site_name'],
            $site['site_description'],
            '/',
            'index, follow',
            null,
            $this->seo->organization($site)
        ), compact('articles', 'projects'));
    }

    public function about()
    {
        return $this->staticPage(
            'Tentang Kami',
            '/tentang',
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            [
                ['heading' => 'Profil Organisasi', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
                ['heading' => 'Komitmen Kami', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.'],
            ]
        );
    }

    public function organization()
    {
        return $this->staticPage(
            'Organisasi',
            '/tentang/organisasi',
            'Sejarah, visi, misi, tujuan, dan profil organisasi.',
            [
                ['heading' => 'Profil Organisasi', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
                ['heading' => 'Sejarah', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
                ['heading' => 'Visi', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
                ['heading' => 'Misi', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
                ['heading' => 'Tujuan', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
            ]
        );
    }

    public function structure()
    {
        $site = $this->settings->all();
        $members = $this->safePaginator(fn () => OrganizationMember::query()
            ->where('is_active', true)->orderBy('sort_order')->orderBy('name')->paginate(12), '/tentang/struktur', 12);

        return $this->render('public.structure', $site, $this->seo->page(
            $site,
            'Struktur Organisasi',
            'Struktur organisasi dan peran anggota.',
            '/tentang/struktur'
        ), compact('members'));
    }

    public function reports()
    {
        $site = $this->settings->all();
        $reports = $this->safePaginator(fn () => FinancialReport::query()
            ->latest('year')->latest('month')->paginate(10), '/tentang/laporan-keuangan');

        return $this->render('public.reports', $site, $this->seo->page(
            $site,
            'Laporan Keuangan Bulanan',
            'Daftar laporan keuangan bulanan organisasi.',
            '/tentang/laporan-keuangan'
        ), compact('reports'));
    }

    public function community()
    {
        return $this->staticPage(
            'Komunitas',
            '/komunitas',
            'Ruang informasi untuk artikel, kerja sama, dan project.',
            [
                ['heading' => 'Artikel', 'content' => 'Baca pembaruan, informasi, dan kegiatan terbaru.'],
                ['heading' => 'Kerja Sama', 'content' => 'Temukan informasi kerja sama dan partner organisasi.'],
                ['heading' => 'Project', 'content' => 'Lihat status dan ringkasan project yang tersedia.'],
            ]
        );
    }

    public function articles(Request $request)
    {
        $site = $this->settings->all();
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'date' => trim((string) $request->query('date', '')),
        ];
        $articles = $this->safePaginator(function () use ($request) {
            $query = Article::published()->with('author')->latest('published_at');
            $keyword = trim((string) $request->query('q', ''));
            $date = trim((string) $request->query('date', ''));

            if ($keyword !== '') {
                $query->where(function ($builder) use ($keyword) {
                    $builder->where('title', 'like', '%' . $keyword . '%')
                        ->orWhere('excerpt', 'like', '%' . $keyword . '%')
                        ->orWhere('meta_description', 'like', '%' . $keyword . '%');
                });
            }

            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $query->whereDate('published_at', $date);
            }

            return $query->paginate(6)->appends($request->query());
        }, '/artikel');

        return $this->render('public.articles.index', $site, $this->seo->page(
            $site,
            'Artikel',
            'Artikel dan informasi terbaru dari organisasi.',
            '/artikel'
        ), compact('articles', 'filters'));
    }

    public function article(string $slug)
    {
        $article = Article::published()->with('author')->where('slug', $slug)->firstOrFail();
        $this->articleViews->record($article);
        $site = $this->settings->all();

        return $this->render('public.articles.show', $site, $this->seo->article($site, $article), [
            'article' => $article,
            'preview' => false,
        ]);
    }

    public function partners()
    {
        $site = $this->settings->all();
        $partners = $this->safePaginator(fn () => Partner::query()->latest('cooperation_year')->latest()->paginate(12), '/kerja-sama', 12);

        return $this->render('public.partners', $site, $this->seo->page(
            $site,
            'Kerja Sama',
            'Daftar kerja sama dan partner organisasi.',
            '/kerja-sama'
        ), compact('partners'));
    }

    public function projects()
    {
        $site = $this->settings->all();
        $projects = $this->safePaginator(fn () => Project::query()->latest('year')->latest()->paginate(12), '/project', 12);

        return $this->render('public.projects', $site, $this->seo->page(
            $site,
            'Project',
            'Daftar project dan status pelaksanaannya.',
            '/project'
        ), compact('projects'));
    }

    public function investors()
    {
        return $this->staticPage(
            'Hubungan Investor',
            '/hubungan-investor',
            'Informasi organisasi, laporan keuangan, dan dokumen publik.',
            [
                ['heading' => 'Profil Organisasi', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
                ['heading' => 'Laporan Keuangan', 'content' => 'Akses laporan keuangan bulanan melalui halaman laporan keuangan.'],
                ['heading' => 'Laporan Tahunan', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
                ['heading' => 'Dokumen Publik', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
                ['heading' => 'Informasi Investor', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
            ]
        );
    }

    public function contact()
    {
        $site = $this->settings->all();

        return $this->render('public.contact', $site, $this->seo->page(
            $site,
            'Hubungi Kami',
            'Kirim pesan atau temukan informasi kontak organisasi.',
            '/kontak'
        ));
    }

    public function cookiePolicy()
    {
        return $this->policyPage('cookie', 'Kebijakan Cookie', '/cookie-policy');
    }

    public function privacyPolicy()
    {
        return $this->policyPage('privacy', 'Kebijakan Privasi', '/privacy-policy');
    }

    private function policyPage(string $kind, string $title, string $path)
    {
        $site = $this->settings->all();
        $policies = require base_path('config/policies.php');
        $policy = $policies[$kind];
        $seo = $this->seo->page($site, $title, $policy['summary'], $path);
        // Draft legal templates must not be indexed as an approved policy.
        $seo['robots'] = 'noindex, follow';

        return $this->render('public.policy', $site, $seo, compact('kind', 'title', 'policy'));
    }

    private function staticPage(string $title, string $path, string $description, array $sections)
    {
        $site = $this->settings->all();

        $reports = $path === '/hubungan-investor'
            ? $this->safeCollection(fn () => FinancialReport::query()->latest('year')->latest('month')->take(3)->get())
            : collect();

        return $this->render('public.static', $site, $this->seo->page($site, $title, $description, $path), compact('title', 'description', 'sections', 'reports'));
    }

    private function render(string $view, array $site, array $seo, array $data = [])
    {
        return view($view, array_merge([
            'site' => $site,
            'seo' => $seo,
            'baseUrl' => rtrim((string) config('app.url'), '/'),
        ], $data));
    }

    private function safeCollection(Closure $query): Collection
    {
        try {
            return $query();
        } catch (Throwable) {
            return collect();
        }
    }

    private function safePaginator(Closure $query, string $path, int $perPage = 6): LengthAwarePaginator
    {
        try {
            return $query();
        } catch (Throwable) {
            return new LengthAwarePaginator([], 0, $perPage, 1, ['path' => $path]);
        }
    }
}
