<?php

namespace EthickS\FiltCMS;

use EthickS\FiltCMS\Commands\FiltCMSCommand;
use EthickS\FiltCMS\Commands\PublishScheduledContent;
use EthickS\FiltCMS\Testing\TestsFiltCMS;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FiltCMSServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filtcms';

    public static string $viewNamespace = 'filtcms';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('ethicks/filtcms');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('filtcms', function ($app) {
            return new \EthickS\FiltCMS\Services\FiltCMSService;
        });
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filtcms/{$file->getFilename()}"),
                ], 'filtcms-stubs');
            }
        }

        // Register Routes
        $this->registerRoutes();

        // Register Blade Components
        $this->registerBladeComponents();

        // Testing
        Testable::mixin(new TestsFiltCMS);
    }

    protected function registerRoutes(): void
    {
        Route::middleware('web')
            ->group(__DIR__ . '/../routes/web.php');
    }

    protected function registerBladeComponents(): void
    {
        $this->loadViewComponentsAs('filtcms', [
            \EthickS\FiltCMS\View\Components\PageContent::class,
            \EthickS\FiltCMS\View\Components\BlogContent::class,
            \EthickS\FiltCMS\View\Components\LatestBlogs::class,
        ]);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'ethicks/filtcms';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filtcms', __DIR__ . '/../resources/dist/components/filtcms.js'),
            Css::make('filtcms-styles', __DIR__ . '/../resources/dist/filtcms.css'),
            Js::make('filtcms-scripts', __DIR__ . '/../resources/dist/filtcms.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FiltCMSCommand::class,
            PublishScheduledContent::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_filtcms_categories_table',
            'create_filtcms_pages_table',
            'create_filtcms_pages_views_table',
            'create_filtcms_blogs_table',
            'create_filtcms_blogs_views_table',
            'create_filtcms_comments_table',
            'create_filtcms_settings_table',
        ];
    }
}
