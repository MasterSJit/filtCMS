<?php

namespace EthickS\FiltCMS\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Category Tabs')
                    ->tabs([
                        Tab::make('Basic Information')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Leave blank to auto-generate from name'),

                                Select::make('parent_id')
                                    ->label('Parent Category')
                                    ->relationship('parent', 'name', fn (Builder $query) => $query->orderBy('name'))
                                    ->searchable()
                                    ->preload()
                                    ->helperText('Select a parent category to create a subcategory'),

                                TextInput::make('order')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Used for sorting categories'),

                                Textarea::make('description')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                FileUpload::make('image')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('categories/images'),
                            ])
                            ->columns(2),

                        Tab::make('SEO')
                            ->schema([
                                TextInput::make('seo_title')
                                    ->maxLength(60)
                                    ->helperText('Optimal length: 50-60 characters'),

                                Textarea::make('seo_description')
                                    ->rows(2)
                                    ->maxLength(160)
                                    ->helperText('Optimal length: 150-160 characters'),

                                TagsInput::make('seo_keywords')
                                    ->separator(','),
                            ])
                            ->columns(2),
                ])
                ->columnSpanFull(),
            ]);
    }
}
