<?php
// SPDX-License-Identifier: NCSA

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', 'Public\\SiteController@home');
$router->get('/tentang', 'Public\\SiteController@about');
$router->get('/tentang/organisasi', 'Public\\SiteController@organization');
$router->get('/tentang/struktur', 'Public\\SiteController@structure');
$router->get('/tentang/laporan-keuangan', 'Public\\SiteController@reports');

$router->get('/komunitas', 'Public\\SiteController@community');
$router->get('/artikel', 'Public\\SiteController@articles');
$router->get('/artikel/{slug}', 'Public\\SiteController@article');
$router->get('/kerja-sama', 'Public\\SiteController@partners');
$router->get('/project', 'Public\\SiteController@projects');
$router->get('/hubungan-investor', 'Public\\SiteController@investors');

$router->get('/kontak', 'Public\\SiteController@contact');
$router->post('/kontak', 'Public\\ContactController@store');
$router->get('/cookie-policy', 'Public\\SiteController@cookiePolicy');
$router->get('/privacy-policy', 'Public\\SiteController@privacyPolicy');

$router->get('/laporan/{id:[0-9]+}/view', 'Public\\ReportDownloadController@view');
$router->get('/laporan/{id:[0-9]+}/download', 'Public\\ReportDownloadController@download');

$router->get('/sitemap.xml', 'Public\\SitemapController@index');
$router->get('/sitemap-pages.xml', 'Public\\SitemapController@pages');
$router->get('/sitemap-articles.xml', 'Public\\SitemapController@articles');
$router->get('/robots.txt', 'Public\\SitemapController@robots');

$router->group(['prefix' => 'admin'], function ($router) {
    $router->get('login', 'Auth\\AdminAuthController@showLogin');
    $router->post('login', 'Auth\\AdminAuthController@login');

    $router->group(['middleware' => 'admin.auth'], function ($router) {
        $router->get('/', 'Admin\\DashboardController@index');
        $router->get('dashboard', 'Admin\\DashboardController@index');
        $router->post('logout', 'Auth\\AdminAuthController@logout');

        $router->get('articles', 'Admin\\ArticleController@index');
        $router->get('articles/create', 'Admin\\ArticleController@create');
        $router->post('articles', 'Admin\\ArticleController@store');
        $router->post('articles/import-docx', 'Admin\\ArticleController@importDocx');
        $router->get('articles/{id:[0-9]+}/preview', 'Admin\\ArticleController@preview');
        $router->get('articles/{id:[0-9]+}/edit', 'Admin\\ArticleController@edit');
        $router->post('articles/{id:[0-9]+}', 'Admin\\ArticleController@update');
        $router->post('articles/{id:[0-9]+}/delete', 'Admin\\ArticleController@destroy');

        $router->get('reports', 'Admin\\ReportController@index');
        $router->get('reports/create', 'Admin\\ReportController@create');
        $router->post('reports', 'Admin\\ReportController@store');
        $router->get('reports/{id:[0-9]+}/edit', 'Admin\\ReportController@edit');
        $router->post('reports/{id:[0-9]+}', 'Admin\\ReportController@update');
        $router->post('reports/{id:[0-9]+}/delete', 'Admin\\ReportController@destroy');

        $router->get('projects', 'Admin\\ProjectController@index');
        $router->get('projects/create', 'Admin\\ProjectController@create');
        $router->post('projects', 'Admin\\ProjectController@store');
        $router->get('projects/{id:[0-9]+}/edit', 'Admin\\ProjectController@edit');
        $router->post('projects/{id:[0-9]+}', 'Admin\\ProjectController@update');
        $router->post('projects/{id:[0-9]+}/delete', 'Admin\\ProjectController@destroy');

        $router->get('partners', 'Admin\\PartnerController@index');
        $router->get('partners/create', 'Admin\\PartnerController@create');
        $router->post('partners', 'Admin\\PartnerController@store');
        $router->get('partners/{id:[0-9]+}/edit', 'Admin\\PartnerController@edit');
        $router->post('partners/{id:[0-9]+}', 'Admin\\PartnerController@update');
        $router->post('partners/{id:[0-9]+}/delete', 'Admin\\PartnerController@destroy');

        $router->get('members', 'Admin\\MemberController@index');
        $router->get('members/create', 'Admin\\MemberController@create');
        $router->post('members', 'Admin\\MemberController@store');
        $router->get('members/{id:[0-9]+}/edit', 'Admin\\MemberController@edit');
        $router->post('members/{id:[0-9]+}', 'Admin\\MemberController@update');
        $router->post('members/{id:[0-9]+}/delete', 'Admin\\MemberController@destroy');

        $router->get('users', 'Admin\\UserController@index');
        $router->post('users/{id:[0-9]+}', 'Admin\\UserController@update');

        $router->get('messages', 'Admin\\MessageController@index');
        $router->post('messages/{id:[0-9]+}', 'Admin\\MessageController@update');
    });
});
