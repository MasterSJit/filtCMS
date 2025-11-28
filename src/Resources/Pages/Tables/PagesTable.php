<?php

namespace EthickS\FiltCMS\Resources\Pages\Tables;

use EthickS\FiltCMS\Models\Page;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->formatStateUsing(fn ($state) => substr($state, 0, 10).(strlen($state) > 10 ? '...' : ''))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(),
                ImageColumn::make('featured_image')
                    ->label('Image')
                    ->toggleable()
                    ->disk('public'),
                TextColumn::make('category.name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'danger',
                        'published' => 'success',
                        'scheduled' => 'warning',
                    }),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('seo_title')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('likes_count')
                    ->label('Likes')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                ToggleColumn::make('comments_enabled')
                    ->label('Comments')
                    ->toggleable(),
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // TrashedFilter::make(),
                SelectFilter::make('published_at')
                    ->label('Published Date')
                    ->options([
                        'today' => 'Today',
                        'this_week' => 'This Week',
                        'this_month' => 'This Month',
                        'this_year' => 'This Year',
                    ])
                    ->query(function ($query, array $data) {
                        if (! isset($data['value'])) {
                            return $query;
                        }

                        return match ($data['value']) {
                            'today' => $query->whereDate('published_at', now()->toDateString()),
                            'this_week' => $query->whereBetween('published_at', [now()->startOfWeek(), now()->endOfWeek()]),
                            'this_month' => $query->whereBetween('published_at', [now()->startOfMonth(), now()->endOfMonth()]),
                            'this_year' => $query->whereBetween('published_at', [now()->startOfYear(), now()->endOfYear()]),
                            default => $query,
                        };
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('published_at', 'desc');
    }

    public static function getTabs(): array
    {
        return [
            'all' => Tab::make('All Pages')
                ->icon(Heroicon::OutlinedDocumentText)
                ->badge(fn () => Page::count()),

            'draft' => Tab::make('Draft')
                ->icon(Heroicon::OutlinedDocument)
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'draft'))
                ->badge(fn () => Page::where('status', 'draft')->count())
                ->badgeColor('danger'),

            'published' => Tab::make('Published')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'published'))
                ->badge(fn () => Page::where('status', 'published')->count())
                ->badgeColor('success'),

            'scheduled' => Tab::make('Scheduled')
                ->icon(Heroicon::OutlinedClock)
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'scheduled'))
                ->badge(fn () => Page::where('status', 'scheduled')->count())
                ->badgeColor('warning'),
        ];
    }
}
