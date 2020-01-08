<?php declare(strict_types=1);

use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Support\Facades\Route;
use SP\Admin\Http\Middleware\Locale;

$login_middleware = [
    'web',
    Locale::class,
];
$main_middleware = [
    'web',
    Locale::class,
    AuthenticateSession::class,
];

Route::prefix('admin')
    ->name('admin.')
    ->namespace('\SP\Admin\Http\Controllers')
    ->group(static function () use ($login_middleware, $main_middleware) {

        Route::redirect('', '/admin/home')->name('to_home');

        Route::middleware($login_middleware)
            ->group(static function () {
                // authentication routes
                Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
                Route::post('login', 'Auth\LoginController@login')->name('login.post');
                Route::post('logout', 'Auth\LoginController@logout')->name('logout');
                // password reset routes
                Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')
                    ->name('password.request');
                Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
                    ->name('password.email');
                Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')
                    ->name('password.reset');
                Route::post('password/reset', 'Auth\ResetPasswordController@reset')
                    ->name('password.update');
            });

        Route::middleware($main_middleware)
            ->group(static function () {
                // home page
                Route::get('home', 'Dashboard\HomeController@index')->name('home');
                // users
                Route::resource('users', 'User\UserController');
                // basic settings
                Route::get('settings/basic', 'Setting\BasicController@show')->name('settings.basic.show');
                Route::get('settings/basic/edit', 'Setting\BasicController@edit')->name('settings.basic.edit');
                Route::match(['PUT', 'PATCH'], 'settings/basic', 'Setting\BasicController@update')
                    ->name('settings.basic.update');
                // analytics settings
                Route::get('settings/analytics', 'Setting\AnalyticsController@show')->name('settings.analytics.show');
                Route::get('settings/analytics/edit',
                    'Setting\AnalyticsController@edit')->name('settings.analytics.edit');
                Route::match(['PUT', 'PATCH'], 'settings/analytics', 'Setting\AnalyticsController@update')
                    ->name('settings.analytics.update');
                // client scripts
                Route::get('settings/scripts', 'Setting\ScriptController@show')->name('settings.scripts.show');
                Route::get('settings/scripts/edit', 'Setting\ScriptController@edit')->name('settings.scripts.edit');
                Route::match(['PUT', 'PATCH'], 'settings/scripts', 'Setting\ScriptController@update')
                    ->name('settings.scripts.update');
                // scheduled tasks
                Route::resource('scheduled-tasks', 'ScheduledTask\ScheduledTaskController');
                // trash bin
                Route::get('trash-bin', 'TrashBin\TrashBinController@index')->name('trash-bin.index');
                Route::delete('trash-bin/clear', 'TrashBin\TrashBinController@clear')->name('trash-bin.clear');
                // user notes
                Route::match(['PUT', 'PATCH'], 'user-notes', 'Dashboard\HomeController@updateNotes')
                    ->name('user-notes.update');
                // php info
                Route::get('php-info', 'Dashboard\HomeController@phpinfo')->name('phpinfo');
                // clears cache
                Route::post('clear-cache', 'Dashboard\HomeController@clearCache')->name('clear-cache');
                // creates sitemap
                Route::post('create-sitemap', 'Dashboard\HomeController@createSitemap')->name('create-sitemap');
                // clears log
                Route::delete('clear-log', 'Dashboard\HomeController@clearLog')->name('clear-log');
            });

    });

// files
Route::namespace('\SP\Admin\Http\Controllers\File')->group(static function () {

    $base_url = config('admin.image_glide_base_url', 'img');
    Route::get("/$base_url/{path}", 'ImageController@show')->where('path', '[a-zA-Z0-9\-_\/]+\.[a-zA-Z0-9]+$');

});

