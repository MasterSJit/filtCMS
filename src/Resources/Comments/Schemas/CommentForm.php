<?php

namespace EthickS\FiltCMS\Resources\Comments\Schemas;

use EthickS\FiltCMS\Models\Comment;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('commentable_type')
                    ->label('Content Type')
                    ->options([
                        'App\\Models\\Blog' => 'Blog Post',
                        'App\\Models\\Page' => 'Page',
                    ])
                    ->required()
                    ->live()
                    ->disabled(fn ($context) => $context === 'edit'),

                Select::make('commentable_id')
                    ->label('Content')
                    ->options(function ($get) {
                        $type = $get('commentable_type');
                        if (! $type) {
                            return [];
                        }

                        return $type::pluck('title', 'id');
                    })
                    ->required()
                    ->searchable()
                    ->disabled(fn ($context) => $context === 'edit'),

                Textarea::make('content')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),

                TextInput::make('author_name')
                    ->maxLength(255)
                    ->visible(fn ($get) => ! $get('user_id')),

                TextInput::make('author_email')
                    ->email()
                    ->maxLength(255)
                    ->visible(fn ($get) => ! $get('user_id')),

                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                Select::make('parent_id')
                    ->label('Reply To')
                    ->relationship('parent', 'id')
                    ->getOptionLabelFromRecordUsing(fn (Comment $record) => substr($record->content, 0, 50).'...'
                    )
                    ->searchable()
                    ->preload(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'spam' => 'Spam',
                    ])
                    ->default('approved')
                    ->required(),

                Toggle::make('is_flagged')
                    ->label('Flagged for Review'),
            ])
            ->columns(2);
    }
}
