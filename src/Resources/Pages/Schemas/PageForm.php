<?php

namespace EthickS\FiltCMS\Resources\Pages\Schemas;

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

class PageForm
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
                                    ->live(debounce: 1000) // debounces update by 1000ms
                                    ->afterStateUpdated(function (string $operation, $state, $set) {
                                        if ($operation === 'create') {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),

                                TextInput::make('slug')
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
                                    ->preserveFilenames()
                                    ->disk('public')
                                    ->directory('images/page'),

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
                                            TagsInput::make('seo_keywords')->separator(','),
                                        ]),
                                    ]),

                                Select::make('status')
                                    ->required()
                                    ->default('draft')
                                    ->options([
                                        'draft' => 'Draft',
                                        'scheduled' => 'Scheduled',
                                        'published' => 'Published',
                                    ]),

                                DateTimePicker::make('published_at')
                                    ->label('Publish Date & Time')
                                    ->default(now()),
                            ])
                            ->columns(2),
                        Tab::make('SEO')
                            ->icon(Heroicon::OutlinedPresentationChartLine)
                            ->schema([
                                TextInput::make('seo_title'),
                                Textarea::make('seo_description'),
                                TagsInput::make('seo_keywords')
                                    ->separator(','),
                            ])
                            ->columns(2),
                        Tab::make('Social')
                            ->icon(Heroicon::OutlinedHeart)
                            ->schema([
                                TextInput::make('views_count')
                                    ->numeric()
                                    ->default(0),
                                TextInput::make('likes_count')
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                                Toggle::make('comments_enabled')
                                    ->required(),
                                Select::make('user_id')
                                    ->relationship('author', 'name')
                                    ->preload()
                                    ->searchable(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
