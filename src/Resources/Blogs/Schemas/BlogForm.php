<?php

namespace EthickS\FiltCMS\Resources\Blogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Content')
                            ->icon(Heroicon::OutlinedPencilSquare)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Leave blank to auto-generate from title'),

                                RichEditor::make('body')
                                    ->required()
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        'paragraph' => ['bold', 'italic', 'underline', 'strike', 'lead', 'small', 'subscript', 'superscript', 'textColor', 'link', 'highlight', 'horizontalRule', 'clearFormatting'],
                                        'heading' => ['h1', 'h2', 'h3'],
                                        'formatting' => ['blockquote', 'codeBlock', 'bulletList', 'orderedList', 'details',  'alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                                        'table' => [
                                            'table',
                                            'tableAddColumnBefore', 'tableAddColumnAfter', 'tableDeleteColumn',
                                            'tableAddRowBefore', 'tableAddRowAfter', 'tableDeleteRow',
                                            'tableMergeCells', 'tableSplitCell',
                                            'tableToggleHeaderRow',
                                            'tableDelete',
                                        ],
                                        'misc' => ['attachFiles', 'grid', 'gridDelete', 'code'], // The `customBlocks` and `mergeTags` tools are also added here if those features are used.
                                        'history' => ['undo', 'redo'],
                                    ]),

                                TagsInput::make('excerpt')
                                    ->separator(','),

                                FileUpload::make('featured_image')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('images/blog'),
                            ])
                            ->columns(2),

                        Tab::make('Settings')
                            ->icon(Heroicon::OutlinedCog)
                            ->schema([
                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Grid::make(2)->schema([ // Wrap fields in a two-column grid
                                            TextInput::make('name')
                                                ->required()
                                                ->maxLength(255)
                                                ->live(debounce: 1000)
                                                ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),

                                            TextInput::make('slug')
                                                ->required()
                                                ->unique(),
                                            Textarea::make('description'),
                                            FileUpload::make('image')->image(),
                                            Select::make('parent_id')->relationship('parent', 'name'),
                                            TextInput::make('order')->required()->numeric()->default(0),
                                            TextInput::make('seo_title'),
                                            Textarea::make('seo_description'),
                                            Textarea::make('seo_keywords'),
                                        ]),
                                    ]),

                                Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'scheduled' => 'Scheduled',
                                    ])
                                    ->default('draft')
                                    ->required()
                                    ->live(),

                                DateTimePicker::make('published_at')
                                    ->label('Publish Date')
                                    ->default(now())
                                    ->required()
                                    ->visible(fn ($get) => in_array($get('status'), ['published', 'scheduled'])),

                                Select::make('user_id')
                                    ->label('Author')
                                    ->relationship('author', 'name')
                                    ->searchable()
                                    ->preload(),

                                TagsInput::make('tags')
                                    ->separator(',')
                                    ->helperText('Press Enter or comma to add tags'),

                                Toggle::make('is_trending')
                                    ->label('Mark as Trending'),

                                Toggle::make('is_featured')
                                    ->label('Mark as Featured'),

                                Toggle::make('comments_enabled')
                                    ->label('Enable Comments')
                                    ->default(true),
                            ])
                            ->columns(2),

                        Tab::make('SEO')
                            ->icon(Heroicon::OutlinedPresentationChartLine)
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
                            ])->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
