<?php

namespace EthickS\FiltCMS\Resources\Comments\Tables;

use EthickS\FiltCMS\Models\Comment;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('commentable_type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => str_replace('App\\Models\\', '', $state)
                    )
                    ->badge()
                    ->sortable(),

                TextColumn::make('commentable.title')
                    ->label('Post/Page')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->toggleable(),

                TextColumn::make('content')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),

                TextColumn::make('author_name')
                    ->label('Author')
                    ->getStateUsing(fn (Comment $record) => $record->user ? $record->user->name : $record->author_name
                    )
                    ->searchable(['author_name'])
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'spam' => 'gray',
                    })
                    ->sortable(),

                IconColumn::make('is_flagged')
                    ->label('Flagged')
                    ->boolean()
                    ->toggleable(),

                TextColumn::make('replies_count')
                    ->counts('replies')
                    ->label('Replies')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'spam' => 'Spam',
                    ]),

                SelectFilter::make('commentable_type')
                    ->label('Type')
                    ->options([
                        'App\\Models\\Blog' => 'Blog',
                        'App\\Models\\Page' => 'Page',
                    ]),

                TernaryFilter::make('is_flagged')
                    ->label('Flagged'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (Comment $record) => $record->status !== 'approved')
                        ->action(fn (Comment $record) => $record->update(['status' => 'approved'])),

                    Action::make('reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (Comment $record) => $record->status !== 'rejected')
                        ->action(fn (Comment $record) => $record->update(['status' => 'rejected'])),

                    Action::make('spam')
                        ->icon('heroicon-o-no-symbol')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->visible(fn (Comment $record) => $record->status !== 'spam')
                        ->action(fn (Comment $record) => $record->update(['status' => 'spam'])),

                    Action::make('reply')
                        ->icon('heroicon-o-chat-bubble-left')
                        ->color('info')
                        ->form([
                            Textarea::make('content')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function (Comment $record, array $data) {
                            Comment::create([
                                'commentable_type' => $record->commentable_type,
                                'commentable_id' => $record->commentable_id,
                                'parent_id' => $record->id,
                                'content' => $data['content'],
                                'user_id' => Auth::id(),
                                'status' => 'approved',
                            ]);
                        }),

                    // EditAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['status' => 'approved']))),

                    BulkAction::make('reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['status' => 'rejected']))),

                    BulkAction::make('spam')
                        ->icon('heroicon-o-no-symbol')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['status' => 'spam']))),

                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
