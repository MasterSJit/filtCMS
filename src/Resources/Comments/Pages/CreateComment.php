<?php

namespace EthickS\FiltCMS\Resources\Comments\Pages;

use EthickS\FiltCMS\Resources\Comments\CommentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateComment extends CreateRecord
{
    protected static string $resource = CommentResource::class;
}
