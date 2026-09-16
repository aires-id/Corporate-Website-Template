<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\DocxImporter;
use App\Services\HtmlSanitizer;
use App\Services\SeoService;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use RuntimeException;

class ArticleController extends Controller
{
    public function __construct(
        private HtmlSanitizer $sanitizer,
        private DocxImporter $docxImporter,
        private SettingsService $settings,
        private SeoService $seo,
    ) {
    }

    public function index(Request $request)
    {
        $user = $this->adminUser($request);
        $query = Article::query()->with('author')->latest('updated_at');
        $keyword = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        if ($keyword !== '') {
            $query->where(function ($builder) use ($keyword) {
                $builder->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('slug', 'like', '%' . $keyword . '%');
            });
        }
        if (in_array($status, ['draft', 'published'], true)) {
            $query->where('status', $status);
        }

        return view('admin.articles.index', [
            'site' => $this->settings->all(),
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'user' => $user,
            'articles' => $query->paginate(10)->appends($request->query()),
            'filters' => ['q' => $keyword, 'status' => $status],
        ]);
    }

    public function create(Request $request)
    {
        return $this->form($request, new Article(), 'Buat Artikel');
    }

    public function store(Request $request)
    {
        $this->adminUser($request);

        return $this->save($request, new Article(), true);
    }

    public function edit(Request $request, int $id)
    {
        return $this->form($request, Article::query()->findOrFail($id), 'Edit Artikel');
    }

    public function update(Request $request, int $id)
    {
        $this->adminUser($request);

        return $this->save($request, Article::query()->findOrFail($id), false);
    }

    public function destroy(Request $request, int $id)
    {
        $this->adminUser($request);
        Article::query()->findOrFail($id)->delete();
        $this->flash('success', 'Artikel telah dihapus.');

        return redirect('/admin/articles');
    }

    public function preview(Request $request, int $id)
    {
        $this->adminUser($request);
        $article = Article::query()->with('author')->findOrFail($id);
        $site = $this->settings->all();

        return view('public.articles.show', [
            'site' => $site,
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'seo' => $this->seo->article($site, $article, true),
            'article' => $article,
            'preview' => true,
        ]);
    }

    public function importDocx(Request $request)
    {
        $this->adminUser($request);
        $validator = app('validator')->make($request->all(), [
            'docx' => ['required', 'file', 'max:5120'],
        ]);

        if ($validator->fails() || !$request->hasFile('docx')) {
            $this->rememberErrors($validator->errors()->all() ?: ['Pilih file DOCX yang valid.']);
            $this->flash('error', 'File DOCX tidak dapat diproses.');

            return redirect('/admin/articles/create');
        }

        try {
            $_SESSION['imported_article_body'] = $this->docxImporter->import($request->file('docx'));
            $this->flash('success', 'Isi DOCX berhasil diimpor. Periksa dan edit sebelum menerbitkan.');
        } catch (RuntimeException $exception) {
            $this->flash('error', $exception->getMessage());
        }

        return redirect('/admin/articles/create');
    }

    private function form(Request $request, Article $article, string $title)
    {
        $user = $this->adminUser($request);
        $importedBody = $_SESSION['imported_article_body'] ?? null;
        unset($_SESSION['imported_article_body']);

        return view('admin.articles.form', [
            'site' => $this->settings->all(),
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'user' => $user,
            'article' => $article,
            'pageTitle' => $title,
            'importedBody' => $importedBody,
        ]);
    }

    private function save(Request $request, Article $article, bool $isNew)
    {
        $validator = app('validator')->make($request->all(), [
            'title' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191'],
            'excerpt' => ['nullable', 'string', 'max:2000'],
            'thumbnail_url' => ['nullable', 'url:https', 'max:2048'],
            'body_html' => ['required', 'string', 'max:500000'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'status' => ['required', 'in:draft,published'],
        ]);

        if ($validator->fails()) {
            $this->rememberOldInput($request);
            $_SESSION['old']['body_html'] = $this->sanitizer->sanitize((string) $request->input('body_html', ''));
            $this->rememberErrors($validator->errors()->all());
            $this->flash('error', 'Artikel belum dapat disimpan. Periksa data yang diisi.');

            return redirect($isNew ? '/admin/articles/create' : '/admin/articles/' . $article->id . '/edit');
        }

        $data = $validator->validated();
        $body = $this->sanitizer->sanitize($data['body_html']);
        if ($body === '') {
            $this->rememberOldInput($request);
            $_SESSION['old']['body_html'] = $body;
            $this->rememberErrors(['Isi artikel tidak memiliki HTML yang diperbolehkan.']);
            $this->flash('error', 'Isi artikel belum dapat disimpan.');

            return redirect($isNew ? '/admin/articles/create' : '/admin/articles/' . $article->id . '/edit');
        }

        $slug = $this->uniqueSlug(($data['slug'] ?? '') ?: $data['title'], $article->id ?: null);
        $article->fill([
            'author_id' => $article->author_id ?: $this->adminUser($request)->id,
            'title' => trim($data['title']),
            'slug' => $slug,
            'excerpt' => trim((string) ($data['excerpt'] ?? '')),
            'thumbnail_url' => ($data['thumbnail_url'] ?? null) ?: null,
            'body_html' => $body,
            'meta_description' => trim((string) ($data['meta_description'] ?? '')),
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? ($article->published_at ?: Carbon::now()) : null,
        ])->save();

        $this->flash('success', $isNew ? 'Artikel berhasil disimpan.' : 'Perubahan artikel berhasil disimpan.');

        return redirect('/admin/articles/' . $article->id . '/edit');
    }

    private function uniqueSlug(string $candidate, ?int $ignoreId): string
    {
        $base = Str::limit(Str::slug($candidate), 170, '');
        if ($base === '') {
            $base = 'artikel';
        }

        $slug = $base;
        $counter = 2;
        while (Article::query()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = Str::limit($base, 170 - strlen((string) $counter), '') . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
