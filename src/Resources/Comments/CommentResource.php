<?php

namespace EthickS\FiltCMS\Resources\Comments;

use BackedEnum;
use EthickS\FiltCMS\Models\Comment;
use EthickS\FiltCMS\Resources\Comments\Pages\CreateComment;
use EthickS\FiltCMS\Resources\Comments\Pages\EditComment;
use EthickS\FiltCMS\Resources\Comments\Pages\ListComments;
use EthickS\FiltCMS\Resources\Comments\Schemas\CommentForm;
use EthickS\FiltCMS\Resources\Comments\Tables\CommentsTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string | UnitEnum | null $navigationGroup = 'FiltCMS';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()
            ->whereIn('status', ['pending', 'spam'])
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::query()
            ->whereIn('status', ['pending', 'spam'])
            ->count();

        return $count > 0 ? 'danger' : null;
    }

    public static function form(Schema $schema): Schema
    {
        return CommentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommentsTable::configure($table);
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
            'index' => ListComments::route('/'),
            'create' => CreateComment::route('/create'),
            'edit' => EditComment::route('/{record}/edit'),
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
