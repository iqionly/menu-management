<?php

namespace Iqionly\MenuManagement;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Iqionly\MenuManagement\Interfaces\DriverTreeView;
use Iqionly\MenuManagement\Models\MenuManagementListMenu;
use Iqionly\MenuManagement\Resources\JSTreeResource;

class MenuManagementServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'menu-management');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'menu-management');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('menu-management.php'),
            ], 'menu-management.config');

            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'menu-management.tables');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/menu-management'),
            ], 'menu-management.views');

            // Publishing assets.
            $this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/menu-management'),
            ], 'menu-management.assets');

            // Publishing the translation files.
            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/menu-management'),
            ], 'menu-management.lang');

            // Registering package commands.
            $this->commands([
                \Iqionly\MenuManagement\Console\Commands\SeedCommand::class
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'menu-management');

        // Register the main class to use with the facade
        $this->app->singleton('menu-management', function (Application $app) {
            $model = app(MenuManagementListMenu::class);
            return new MenuManagement($app, $model);
        });

        // Run register
        $this->register_driver();

        $this->register_models();

        $this->loadViewComponentsAs('menu-management', [
            'editor' => \Iqionly\MenuManagement\Views\Components\Editor::class,
            'navigation' => \Iqionly\MenuManagement\Views\Components\Navigation::class,
        ]);
        
        Blade::directive('menu_management_js', function ($expression) {
            return "<?php echo app('menu-management')->get_tree_scripts(); echo app('menu-management')->get_tree_assets(); ?>";
        });
    }

    // Register the driver tree for editor menu
    protected function register_driver()
    {
        $this->app->singleton('menu-management-data', function(Application $app) {
            return $app->get('menu-management')->get_model_menu();
        });

        // Bind driver to JSTreeResource, in the future we need to change this to config based, becuase if user want to change tree edit we need to specify
        $this->app->bind(DriverTreeView::class, function (Application $app, $parameters) {
            // JSONResponse need to disable wrap function in this bind, sad we can disable it in the class for now T_T
            JSTreeResource::withoutWrapping();
            // [ass the parameters
            return new JSTreeResource($parameters);
        }, true);
    }

    protected function register_models()
    {
        $this->app->bind(MenuManagementListMenu::class, function($app, $parameters) {
            return new MenuManagementListMenu($parameters);
        });
    }

}
