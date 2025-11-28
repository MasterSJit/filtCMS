<?php

namespace EthickS\FiltCMS\Resources\Blogs\Pages;

use EthickS\FiltCMS\Resources\Blogs\BlogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;
}
