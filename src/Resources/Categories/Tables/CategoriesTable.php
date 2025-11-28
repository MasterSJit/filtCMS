<?php

namespace EthickS\FiltCMS\Resources\Categories\Tables;

use EthickS\FiltCMS\Models\Category;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('hierarchy')
                    ->label('Name')
                    ->state(function (Category $record): string {
                        $indent = '';
                        $parent = $record->parent;
                        $level = 0;

                        while ($parent) {
                            $level++;
                            $parent = $parent->parent;
                        }

                        $indent = str_repeat('━━ ', $level);

                        return $indent . $record->name;
                    })
                    ->searchable(query: function ($query, string $search) {
                        return $query->where('name', 'like', "%{$search}%");
                    })
                    ->sortable(query: function ($query, string $direction) {
                        return $query->orderBy('order', $direction);
                    })
                    ->description(fn (Category $record): string => $record->description ?? ''),

                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable()
                    ->copyable(),

                TextColumn::make('parent.name')
                    ->label('Parent Category')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                ImageColumn::make('image')
                    ->circular()
                    ->toggleable(),

                TextColumn::make('order')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('pages_count')
                    ->counts('pages')
                    ->label('Pages')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('blogs_count')
                    ->counts('blogs')
                    ->label('Blogs')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('parent_id')
                    ->label('Parent Category')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload(),

                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('move_to_parent')
                    ->label('Move To')
                    ->icon('heroicon-o-arrows-right-left')
                    ->color('info')
                    ->form([
                        Select::make('parent_id')
                            ->label('New Parent Category')
                            ->options(function (Category $record) {
                                return Category::query()
                                    ->where('id', '!=', $record->id)
                                    ->whereNotIn('id', function ($query) use ($record) {
                                        // Prevent moving to own descendants
                                        $query->select('id')
                                            ->from('filtcms_categories')
                                            ->where('parent_id', $record->id);
                                    })
                                    ->pluck('name', 'id')
                                    ->prepend('None (Top Level)', null);
                            })
                            ->searchable()
                            ->helperText('Select a parent category or leave as None for top-level category'),
                    ])
                    ->action(function (Category $record, array $data) {
                        $record->update([
                            'parent_id' => $data['parent_id'],
                        ]);
                    })
                    ->successNotificationTitle('Category moved successfully'),

                // EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->reorderRecordsTriggerAction(
                fn ($action, $livewire) => $action
                    ->button()
                    ->label('Reorder Categories')
            )
            ->groups([
                \Filament\Tables\Grouping\Group::make('parent.name')
                    ->label('Parent Category')
                    ->collapsible(),
            ]);
    }
}
