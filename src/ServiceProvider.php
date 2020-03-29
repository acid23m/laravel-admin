<?php
declare(strict_types=1);

namespace SP\Admin;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use SP\Admin\Console\Commands\AdminInstallCommand;
use SP\Admin\Console\Commands\SitemapCommand;
use SP\Admin\Contracts\Setting\AbstractBasic;
use SP\Admin\Contracts\Setting\AbstractBasicRepository;
use SP\Admin\Contracts\Setting\AbstractBasicRequest;
use SP\Admin\Events\Setting\BasicSaved as BasicSavedEvent;
use SP\Admin\Events\User\CreatingUser as CreatingUserEvent;
use SP\Admin\Events\User\SavingUser as SavingUserEvent;
use SP\Admin\Http\Requests\Setting\UpdateBasic;
use SP\Admin\Listeners\Setting\BasicSaved as BasicSavedListener;
use SP\Admin\Listeners\User\CreatingUser as CreatingUserListener;
use SP\Admin\Listeners\User\SavingUser as SavingUserListener;
use SP\Admin\Listeners\User\UserLogin;
use SP\Admin\Models\Repositories\SettingBasicRepository;
use SP\Admin\Models\SettingBasic;
use SP\Admin\Security\Role;
use SP\Admin\View\Components\ModelSort;
use SP\Admin\View\Components\Toast;

/**
 * Extension mount point.
 *
 * @package SP\Admin
 */
final class ServiceProvider extends BaseServiceProvider
{
    /**
     * Registers the application services.
     */
    public function register(): void
    {
        // configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/auth_guards.php', 'auth.guards');
        $this->mergeConfigFrom(__DIR__ . '/../config/auth_providers.php', 'auth.providers');
        $this->mergeConfigFrom(__DIR__ . '/../config/auth_passwords.php', 'auth.passwords');
        $this->mergeConfigFrom(__DIR__ . '/../config/database_connections.php', 'database.connections');

        // basic settings
        $basic_settings_class = config('admin.settings.basic_class', SettingBasic::class);
        $this->app->singleton(
            AbstractBasic::class,
            fn(Application $app) => new $basic_settings_class
        );
        $this->app->bind(
            AbstractBasicRepository::class,
            config('admin.settings.basic_repository_class', SettingBasicRepository::class)
        );
        $this->app->bind(
            AbstractBasicRequest::class,
            config('admin.settings.basic_request_class', UpdateBasic::class)
        );

        // constants
        \defined('ADMIN_PACKAGE_VERSION') or \define('ADMIN_PACKAGE_VERSION', '1.4.7');
        \defined('ADMIN_PACKAGE_PATH') or \define('ADMIN_PACKAGE_PATH', dirname(__DIR__));
        \defined('STANDARD_FORMAT__DATE') or \define('STANDARD_FORMAT__DATE', 'Y-m-d');
        \defined('STANDARD_FORMAT__DATETIME') or \define('STANDARD_FORMAT__DATETIME', 'Y-m-d H:i:s');
    }

    /**
     * Bootstraps the application services.
     */
    public function boot(): void
    {
        // routes
        $this->loadRoutes();
        // assets
        $this->loadAssets('assets');
        // configuration
        $this->loadConfig('configs');
        // views
        $this->loadViews('views');
        // view extends
        $this->extendViews();
        // translations
        $this->loadTranslates('views');
        // adminer
        $this->loadAdminer('adminer');
        // commands
        $this->loadCommands();
        // gates
        $this->registerGates();
        // listeners
        $this->registerListeners();
        // settings
        $this->applySettings();
    }

    /**
     * Routes.
     */
    private function loadRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    /**
     * Assets.
     *
     * @param string $tag
     */
    private function loadAssets(string $tag): void
    {
        $this->publishes([
            __DIR__ . '/../resources/css/dist' => public_path('admin-assets/css'),
            __DIR__ . '/../resources/css/fonts' => public_path('admin-assets/fonts'),
            __DIR__ . '/../resources/js/dist' => public_path('admin-assets/js'),
            __DIR__ . '/../node_modules/@fortawesome/fontawesome-free/webfonts' => public_path('admin-assets/webfonts'),
        ], $tag);
    }

    /**
     * Configuration.
     *
     * @param string $tag
     */
    private function loadConfig(string $tag): void
    {
        $this->publishes([
            __DIR__ . '/../config/admin.php' => config_path('admin.php'),
            __DIR__ . '/../config/columnsortable.php' => config_path('columnsortable.php'),
        ], $tag);
    }

    /**
     * Views.
     *
     * @param string $tag
     */
    private function loadViews(string $tag): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');

        $this->publishes([
            __DIR__ . '/../resources/views/nav' => resource_path('views/vendor/admin/nav'),
            __DIR__ . '/../resources/views/settings/basic/_form.blade.php' => resource_path('views/vendor/admin/settings/basic/_form.blade.php'),
        ], $tag);
    }

    /**
     * View extends.
     */
    private function extendViews(): void
    {
        Blade::component(Toast::class, 'toast');
        Blade::component(ModelSort::class, 'model-sort');

        Blade::directive('modelGrid', static function ($expression) {
            return "<?= (new \SP\Admin\View\Widgets\ModelGrid\ModelGrid($expression))->render() ?>";
        });
        Blade::directive('modelDetails', static function ($expression) {
            return "<?= (new \SP\Admin\View\Widgets\ModelDetails\ModelDetails($expression))->render() ?>";
        });
    }

    /**
     * Translations.
     *
     * @param string $tag
     */
    private function loadTranslates(string $tag): void
    {
        $this->publishes([
            __DIR__ . '/../resources/lang/ru.json' => resource_path('lang/vendor/admin/ru.json'),
            __DIR__ . '/../resources/lang/ru' => resource_path('lang/ru'),
        ], $tag);

        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang');
        $this->loadJsonTranslationsFrom(resource_path('lang/vendor/admin'));
    }

    /**
     * Adminer.
     *
     * @param string $tag
     */
    private function loadAdminer(string $tag): void
    {
        $this->publishes([
            __DIR__ . '/../resources/adminer' => public_path('adminer'),
        ], $tag);
    }

    /**
     * Commands.
     */
    private function loadCommands(): void
    {
//        if ($this->app->runningInConsole()) {
        $this->commands([
            AdminInstallCommand::class,
            SitemapCommand::class,
        ]);
//        }
    }

    /**
     * Gates.
     */
    private function registerGates(): void
    {
        Gate::define(Role::SUPER, static function ($user): bool {
            return $user->role === Role::SUPER;
        });

        Gate::define(Role::ADMIN, static function ($user): bool {
            return $user->role === Role::ADMIN || $user->role === Role::SUPER;
        });
    }

    /**
     * Listeners.
     */
    private function registerListeners(): void
    {
        Event::listen(SavingUserEvent::class, SavingUserListener::class);
        Event::listen(CreatingUserEvent::class, CreatingUserListener::class);

        Event::listen(Login::class, UserLogin::class);

        Event::listen(BasicSavedEvent::class, BasicSavedListener::class);
    }

    /**
     * Settings.
     */
    private function applySettings(): void
    {
        $basic_settings = basic_settings();
        $config = [];

        if (isset($basic_settings['app_name'])) {
            $config['app.name'] = $basic_settings['app_name'];
            $config['mail.from.name'] = $basic_settings['app_name'];
        }
        if (isset($basic_settings['timezone'])) {
            $config['app.timezone'] = $basic_settings['timezone'];
        }
        if (isset($basic_settings['mail_gate_host'])) {
            $config['mail.mailers.smtp.host'] = $basic_settings['mail_gate_host'];
        }
        if (isset($basic_settings['mail_gate_port'])) {
            $config['mail.mailers.smtp.port'] = $basic_settings['mail_gate_port'];
        }
        if (isset($basic_settings['mail_gate_encryption'])) {
            $config['mail.mailers.smtp.encryption'] = $basic_settings['mail_gate_encryption'];
        }
        if (isset($basic_settings['mail_gate_login'])) {
            $config['mail.mailers.smtp.username'] = $basic_settings['mail_gate_login'];
            $config['mail.from.address'] = $basic_settings['mail_gate_login'];
        }
        if (isset($basic_settings['mail_gate_password'])) {
            $config['mail.mailers.smtp.password'] = $basic_settings['mail_gate_password'];
        }

        if (!empty($config)) {
            config($config);
        }
    }

}
