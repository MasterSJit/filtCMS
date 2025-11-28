<?php

namespace EthickS\FiltCMS\Resources\Blogs;

use EthickS\FiltCMS\Resources\Blogs\Pages\CreateBlog;
use EthickS\FiltCMS\Resources\Blogs\Pages\EditBlog;
use EthickS\FiltCMS\Resources\Blogs\Pages\ListBlogs;
use EthickS\FiltCMS\Resources\Blogs\Schemas\BlogForm;
use EthickS\FiltCMS\Resources\Blogs\Tables\BlogsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;
use EthickS\FiltCMS\Models\Blog;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;

    protected static ?string $recordTitleAttribute = 'Blog';

    protected static string | UnitEnum | null $navigationGroup = 'FiltCMS';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return BlogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBlogs::route('/'),
            'create' => CreateBlog::route('/create'),
            'edit' => EditBlog::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
