<?php

namespace EthickS\FiltCMS\Resources\Categories;

use BackedEnum;
use EthickS\FiltCMS\Models\Category;
use EthickS\FiltCMS\Resources\Categories\Pages\CreateCategory;
use EthickS\FiltCMS\Resources\Categories\Pages\EditCategory;
use EthickS\FiltCMS\Resources\Categories\Pages\ListCategories;
use EthickS\FiltCMS\Resources\Categories\Schemas\CategoryForm;
use EthickS\FiltCMS\Resources\Categories\Tables\CategoriesTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $slug = 'filtcms/categories';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedFolder;

    protected static string | UnitEnum | null $navigationGroup = 'FiltCMS';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
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
