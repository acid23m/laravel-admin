<?php declare(strict_types=1);

use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Support\Facades\Route;
use SP\Admin\Http\Controllers\Auth\ForgotPasswordController;
use SP\Admin\Http\Controllers\Auth\LoginController;
use SP\Admin\Http\Controllers\Auth\ResetPasswordController;
use SP\Admin\Http\Controllers\Dashboard\HomeController;
use SP\Admin\Http\Controllers\File\ImageController;
use SP\Admin\Http\Controllers\ScheduledTask\ScheduledTaskController;
use SP\Admin\Http\Controllers\Setting\AnalyticsController;
use SP\Admin\Http\Controllers\Setting\BasicController;
use SP\Admin\Http\Controllers\Setting\ScriptController;
use SP\Admin\Http\Controllers\TrashBin\TrashBinController;
use SP\Admin\Http\Controllers\User\UserController;
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
//    ->namespace('\SP\Admin\Http\Controllers')
    ->group(static function () use ($login_middleware, $main_middleware) {

        Route::redirect('', '/admin/home')->name('to_home');

        Route::middleware($login_middleware)
            ->group(static function () {
                // authentication routes
//                Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
                Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
//                Route::post('login', 'Auth\LoginController@login')->name('login.post');
                Route::post('login', [LoginController::class, 'login'])->name('login.post');
//                Route::post('logout', 'Auth\LoginController@logout')->name('logout');
                Route::post('logout', [LoginController::class, 'logout'])->name('logout');
                // password reset routes
//                Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')
                Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
                    ->name('password.request');
//                Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
                Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
                    ->name('password.email');
//                Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')
                Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
                    ->name('password.reset');
//                Route::post('password/reset', 'Auth\ResetPasswordController@reset')
                Route::post('password/reset', [ResetPasswordController::class, 'reset'])
                    ->name('password.update');
            });

        Route::middleware($main_middleware)
            ->group(static function () {
                // home page
//                Route::get('home', 'Dashboard\HomeController@index')->name('home');
                Route::get('home', [HomeController::class, 'index'])->name('home');
                // users
                Route::resource('users', UserController::class);
                // basic settings
//                Route::get('settings/basic', 'Setting\BasicController@show')->name('settings.basic.show');
                Route::get('settings/basic', [BasicController::class, 'show'])->name('settings.basic.show');
//                Route::get('settings/basic/edit', 'Setting\BasicController@edit')->name('settings.basic.edit');
                Route::get('settings/basic/edit', [BasicController::class, 'edit'])->name('settings.basic.edit');
//                Route::match(['PUT', 'PATCH'], 'settings/basic', 'Setting\BasicController@update')
                Route::match(['PUT', 'PATCH'], 'settings/basic', [BasicController::class, 'update'])
                    ->name('settings.basic.update');
                // analytics settings
//                Route::get('settings/analytics', 'Setting\AnalyticsController@show')->name('settings.analytics.show');
                Route::get('settings/analytics', [AnalyticsController::class, 'show'])->name('settings.analytics.show');
//                Route::get('settings/analytics/edit', 'Setting\AnalyticsController@edit')->name('settings.analytics.edit');
                Route::get('settings/analytics/edit', [AnalyticsController::class, 'edit'])->name('settings.analytics.edit');
//                Route::match(['PUT', 'PATCH'], 'settings/analytics', 'Setting\AnalyticsController@update')
                Route::match(['PUT', 'PATCH'], 'settings/analytics', [AnalyticsController::class, 'update'])
                    ->name('settings.analytics.update');
                // client scripts
//                Route::get('settings/scripts', 'Setting\ScriptController@show')->name('settings.scripts.show');
                Route::get('settings/scripts', [ScriptController::class, 'show'])->name('settings.scripts.show');
//                Route::get('settings/scripts/edit', 'Setting\ScriptController@edit')->name('settings.scripts.edit');
                Route::get('settings/scripts/edit', [ScriptController::class, 'edit'])->name('settings.scripts.edit');
//                Route::match(['PUT', 'PATCH'], 'settings/scripts', 'Setting\ScriptController@update')
                Route::match(['PUT', 'PATCH'], 'settings/scripts', [ScriptController::class, 'update'])
                    ->name('settings.scripts.update');
                // scheduled tasks
                Route::resource('scheduled-tasks', ScheduledTaskController::class);
                // trash bin
//                Route::get('trash-bin', 'TrashBin\TrashBinController@index')->name('trash-bin.index');
                Route::get('trash-bin', [TrashBinController::class, 'index'])->name('trash-bin.index');
//                Route::delete('trash-bin/clear', 'TrashBin\TrashBinController@clear')->name('trash-bin.clear');
                Route::delete('trash-bin/clear', [TrashBinController::class, 'clear'])->name('trash-bin.clear');
                // user notes
//                Route::match(['PUT', 'PATCH'], 'user-notes', 'Dashboard\HomeController@updateNotes')
                Route::match(['PUT', 'PATCH'], 'user-notes', [HomeController::class, 'updateNotes'])
                    ->name('user-notes.update');
                // php info
//                Route::get('php-info', 'Dashboard\HomeController@phpinfo')->name('phpinfo');
                Route::get('php-info', [HomeController::class, 'phpinfo'])->name('phpinfo');
                // clears cache
//                Route::post('clear-cache', 'Dashboard\HomeController@clearCache')->name('clear-cache');
                Route::post('clear-cache', [HomeController::class, 'clearCache'])->name('clear-cache');
                // creates sitemap
//                Route::post('create-sitemap', 'Dashboard\HomeController@createSitemap')->name('create-sitemap');
                Route::post('create-sitemap', [HomeController::class, 'createSitemap'])->name('create-sitemap');
                // clears log
//                Route::delete('clear-log', 'Dashboard\HomeController@clearLog')->name('clear-log');
                Route::delete('clear-log', [HomeController::class, 'clearLog'])->name('clear-log');
            });

    });

// files
/*Route::namespace('\SP\Admin\Http\Controllers\File')->group(static function () {

    $base_url = config('admin.image_resizer.base_url', 'img');
    Route::get("/$base_url/{path}", 'ImageController@show')->where('path', '[a-zA-Z0-9\-_\/]+\.[a-zA-Z0-9]+$');

});*/
$base_url = config('admin.image_resizer.base_url', 'img');
Route::get("/$base_url/{path}", [ImageController::class, 'show'])
    ->where('path', '[a-zA-Z0-9\-_\/]+\.[a-zA-Z0-9]+$');
