<?php

namespace EthickS\FiltCMS\Commands;

use Illuminate\Console\Command;

class FiltCMSCommand extends Command
{
    public $signature = 'filtcms:install';

    public $description = 'Install FiltCMS Plugin';

    public function handle(): int
    {
        $this->info('Installing FiltCMS...');

        $this->call('vendor:publish', [
            '--tag' => 'filtcms-config',
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'filtcms-migrations',
        ]);

        if ($this->confirm('Do you want to run migrations now?', true)) {
            $this->call('migrate');
        }

        $this->info('FiltCMS has been installed successfully!');
        $this->newLine();
        $this->info('Next steps:');
        $this->info('1. Register the plugin in your Filament panel provider');
        $this->info('2. Add FiltCMSPlugin::make() to your panel plugins');
        $this->info('3. Start creating pages, blogs, and categories!');

        return self::SUCCESS;
    }
}
