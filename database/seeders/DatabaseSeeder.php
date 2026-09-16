<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $settings = [
            'site_name' => '[ISI NAMA]',
            'site_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'address' => '[ISI ALAMAT]',
            'email' => '[ISI EMAIL]',
            'phone' => '[ISI NOMOR KONTAK]',
            'business_hours' => 'Senin–Jumat, 09.00–17.00 WIB',
            'primary_color' => '#2F6D78',
            'secondary_color' => '#DCEFEB',
            'text_color' => '#18323F',
            'footer_color' => '#112A38',
            'logo_svg' => '',
            'logo_png' => '',
        ];

        foreach ($settings as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $adminEmail = (string) env('ADMIN_SEED_EMAIL', 'admin@example.test');
        $adminPassword = (string) env('ADMIN_SEED_PASSWORD', 'GantiPasswordSebelumProduksi!');
        if (strtolower((string) env('APP_ENV', 'local')) === 'production'
            && ($adminEmail === 'admin@example.test' || $adminPassword === 'GantiPasswordSebelumProduksi!')) {
            throw new RuntimeException('Atur ADMIN_SEED_EMAIL dan ADMIN_SEED_PASSWORD yang unik sebelum menjalankan seeder di production.');
        }

        $admin = User::query()->updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Administrator Lokal',
                'password' => password_hash($adminPassword, PASSWORD_DEFAULT),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        foreach ([
            ['title' => '[Judul Artikel Pertama]', 'slug' => 'artikel-placeholder-pertama'],
            ['title' => '[Judul Artikel Kedua]', 'slug' => 'artikel-placeholder-kedua'],
            ['title' => '[Judul Artikel Ketiga]', 'slug' => 'artikel-placeholder-ketiga'],
        ] as $article) {
            Article::query()->updateOrCreate(
                ['slug' => $article['slug']],
                [
                    'author_id' => $admin->id,
                    'title' => $article['title'],
                    'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'body_html' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>',
                    'meta_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'status' => 'published',
                    'published_at' => Carbon::now(),
                ]
            );
        }
    }
}
