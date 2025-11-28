<?php

namespace EthickS\FiltCMS\Resources\Categories\Pages;

use EthickS\FiltCMS\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class ManageCategories extends Page
{
    use InteractsWithRecord;

    protected static string $resource = CategoryResource::class;

    protected string $view = 'volt-livewire::filament.resources.categories.pages.manage-categories';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
