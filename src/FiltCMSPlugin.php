<?php

namespace EthickS\FiltCMS;

use EthickS\FiltCMS\Pages\SettingsPage;
use EthickS\FiltCMS\Resources\Blogs\BlogResource;
use EthickS\FiltCMS\Resources\Categories\CategoryResource;
use EthickS\FiltCMS\Resources\Comments\CommentResource;
use EthickS\FiltCMS\Resources\Pages\PageResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class FiltCMSPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filtcms';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                PageResource::class,
                CategoryResource::class,
                BlogResource::class,
                CommentResource::class,
            ])
            ->pages([
                SettingsPage::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
