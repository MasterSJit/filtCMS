<?php

namespace EthickS\FiltCMS\Resources\Blogs\Tables;

use EthickS\FiltCMS\Models\Blog;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->formatStateUsing(fn ($state) => Str::limit($state, 10))
                    // ->description(fn (Blog $record): string => Str::limit($record->excerpt ?? '', 10))
                    ->toggleable(),

                ImageColumn::make('featured_image')
                    ->label('Image')
                    ->circular()
                    ->disk('public')
                    ->toggleable(),

                TextColumn::make('category.name')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'scheduled' => 'warning',
                    })
                    ->sortable()
                    ->toggleable(),

                ToggleColumn::make('is_trending')
                    ->label('Trending')
                    ->toggleable(),

                ToggleColumn::make('is_featured')
                    ->label('Featured')
                    ->toggleable(),

                TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable()
                    ->icon('heroicon-m-eye')
                    ->toggleable(),

                TextColumn::make('likes_count')
                    ->label('Likes')
                    ->sortable()
                    ->icon('heroicon-m-heart')
                    ->toggleable(),

                TextColumn::make('comments_count')
                    ->counts('comments')
                    ->label('Comments')
                    ->sortable()
                    ->icon('heroicon-m-chat-bubble-left')
                    ->toggleable(),

                TextColumn::make('author.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->toggleable(),

                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'scheduled' => 'Scheduled',
                    ]),

                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_trending')
                    ->label('Trending'),

                TernaryFilter::make('is_featured')
                    ->label('Featured'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('published_at', 'desc');
    }

    public static function getTabs(): array
    {
        return [
            'all' => Tab::make('All Blogs')
                ->icon(Heroicon::OutlinedNewspaper)
                ->badge(fn () => Blog::count()),

            'draft' => Tab::make('Draft')
                ->icon(Heroicon::OutlinedDocument)
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'draft'))
                ->badge(fn () => Blog::where('status', 'draft')->count())
                ->badgeColor('danger'),

            'published' => Tab::make('Published')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'published'))
                ->badge(fn () => Blog::where('status', 'published')->count())
                ->badgeColor('success'),

            'scheduled' => Tab::make('Scheduled')
                ->icon(Heroicon::OutlinedClock)
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'scheduled'))
                ->badge(fn () => Blog::where('status', 'scheduled')->count())
                ->badgeColor('warning'),
        ];
    }
}
