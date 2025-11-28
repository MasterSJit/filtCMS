<?php

namespace EthickS\FiltCMS\Resources\Blogs\Pages;

use EthickS\FiltCMS\Resources\Blogs\BlogResource;
use EthickS\FiltCMS\Resources\Blogs\Tables\BlogsTable;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBlogs extends ListRecords
{
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return BlogsTable::getTabs();
    }
}
