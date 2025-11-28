<?php

namespace EthickS\FiltCMS\Resources\Pages\Pages;

use EthickS\FiltCMS\Resources\Pages\PageResource;
use EthickS\FiltCMS\Resources\Pages\Tables\PagesTable;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return PagesTable::getTabs();
    }
}
