<?php

namespace EthickS\FiltCMS\Pages;

use BackedEnum;
use EthickS\FiltCMS\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use UnitEnum;

class SettingsPage extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string | UnitEnum | null $navigationGroup = 'FiltCMS';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filtcms::pages.settings';

    protected static ?string $title = 'CMS Settings';

    protected static ?string $navigationLabel = 'Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getSettingsData());
    }

    protected function getSettingsData(): array
    {
        return [
            // Comment Settings
            'comments_enabled' => Setting::get('comments_enabled', true),
            'moderate_comments' => Setting::get('moderate_comments', false),
            'notify_admin_on_comment' => Setting::get('notify_admin_on_comment', true),
            'profanity_words' => Setting::get('profanity_words', ''),

            // Blog Settings
            'posts_per_page' => Setting::get('posts_per_page', 10),
            'allow_guest_posts' => Setting::get('allow_guest_posts', false),
            'default_post_status' => Setting::get('default_post_status', 'draft'),

            // Page Settings
            'default_page_template' => Setting::get('default_page_template', 'default'),
            'allow_page_comments' => Setting::get('allow_page_comments', true),

            // Layout Settings
            'app_layout_type' => Setting::get('app_layout_type', 'component'),
            'app_layout' => Setting::get('app_layout', ''),
            'app_layout_section' => Setting::get('app_layout_section', 'content'),
            'inertia_page_component' => Setting::get('inertia_page_component', 'FiltCMS/Blog'),

            // Blog View Settings
            'blog_index_view_type' => Setting::get('blog_index_view_type', 'default'),
            'blog_index_view_file' => Setting::get('blog_index_view_file', ''),
            'blog_index_custom_html' => Setting::get('blog_index_custom_html', ''),
            'blog_index_custom_css' => Setting::get('blog_index_custom_css', ''),
            'blog_index_custom_js' => Setting::get('blog_index_custom_js', ''),
            'blog_show_view_type' => Setting::get('blog_show_view_type', 'default'),
            'blog_show_view_file' => Setting::get('blog_show_view_file', 'blog.show'),
            'blog_show_custom_html' => Setting::get('blog_show_custom_html', ''),
            'blog_show_custom_css' => Setting::get('blog_show_custom_css', ''),
            'blog_show_custom_js' => Setting::get('blog_show_custom_js', ''),

            // Page View Settings
            'page_show_view_type' => Setting::get('page_show_view_type', 'default'),
            'page_show_view_file' => Setting::get('page_show_view_file', ''),
            'page_show_custom_html' => Setting::get('page_show_custom_html', ''),
            'page_show_custom_css' => Setting::get('page_show_custom_css', ''),
            'page_show_custom_js' => Setting::get('page_show_custom_js', ''),

            // SEO Settings
            'default_meta_title' => Setting::get('default_meta_title', ''),
            'default_meta_description' => Setting::get('default_meta_description', ''),
            'default_meta_keywords' => Setting::get('default_meta_keywords', ''),

            // Social Media Settings
            'facebook_url' => Setting::get('facebook_url', ''),
            'twitter_url' => Setting::get('twitter_url', ''),
            'instagram_url' => Setting::get('instagram_url', ''),
            'linkedin_url' => Setting::get('linkedin_url', ''),
            'enable_share_buttons' => Setting::get('enable_share_buttons', true),

            // Notification Settings
            'email_notifications' => Setting::get('email_notifications', true),
            'notification_email' => Setting::get('notification_email', ''),

            // Advanced Settings
            'custom_css' => Setting::get('custom_css', ''),
            'custom_js' => Setting::get('custom_js', ''),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tab::make('General')
                            ->schema([
                                Section::make('Comment Settings')
                                    ->schema([
                                        Toggle::make('comments_enabled')
                                            ->label('Enable Comments')
                                            ->helperText('Allow comments on blog posts and pages'),

                                        Toggle::make('moderate_comments')
                                            ->label('Moderate Comments')
                                            ->helperText('Hold comments for moderation before publishing'),

                                        Toggle::make('notify_admin_on_comment')
                                            ->label('Notify Admin on New Comment')
                                            ->helperText('Send email notification when a new comment is posted'),

                                        Textarea::make('profanity_words')
                                            ->label('Profanity Filter Words')
                                            ->rows(3)
                                            ->helperText('Comma-separated list of words to filter'),
                                    ])
                                    ->columns(2),

                                Section::make('Blog Settings')
                                    ->schema([
                                        TextInput::make('posts_per_page')
                                            ->label('Posts Per Page')
                                            ->numeric()
                                            ->default(10)
                                            ->required(),

                                        Toggle::make('allow_guest_posts')
                                            ->label('Allow Guest Posts')
                                            ->helperText('Allow non-registered users to submit posts'),

                                        Select::make('default_post_status')
                                            ->label('Default Post Status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'published' => 'Published',
                                            ])
                                            ->default('draft')
                                            ->required(),
                                    ])
                                    ->columns(2),

                                Section::make('Page Settings')
                                    ->schema([
                                        TextInput::make('default_page_template')
                                            ->label('Default Page Template')
                                            ->default('default'),

                                        Toggle::make('allow_page_comments')
                                            ->label('Allow Page Comments')
                                            ->helperText('Enable comments on pages by default'),
                                    ])
                                    ->columns(2),

                                Section::make('Layout Settings')
                                    ->description('Configure the layout used for rendering blog and page content based on your Laravel starter kit')
                                    ->schema([
                                        Select::make('app_layout_type')
                                            ->label('Frontend Stack')
                                            ->options([
                                                'blade' => 'Blade Only (None) - @extends layout',
                                                'component' => 'Livewire / Flux - Component layout',
                                                'inertia' => 'Inertia (React/Vue) - JSON response',
                                            ])
                                            ->default('component')
                                            ->helperText('Select based on what you chose during Laravel installation (php artisan breeze:install). Leave Layout empty to use FiltCMS default.')
                                            ->live()
                                            ->required(),

                                        TextInput::make('app_layout')
                                            ->label(fn ($get) => match($get('app_layout_type')) {
                                                'blade' => 'Layout View File',
                                                'component' => 'Layout Component',
                                                'inertia' => 'Inertia Layout Component',
                                                default => 'Layout',
                                            })
                                            ->placeholder(fn ($get) => match($get('app_layout_type')) {
                                                'blade' => 'layouts.app',
                                                'component' => 'layouts.app.sidebar',
                                                'inertia' => 'layouts/AuthenticatedLayout',
                                                default => '',
                                            })
                                            ->helperText(fn ($get) => match($get('app_layout_type')) {
                                                'blade' => 'View path for @extends (e.g., layouts.app). Leave empty to use FiltCMS default layout.',
                                                'component' => 'Component name for <x-...> (e.g., layouts.app.sidebar). Leave empty to use FiltCMS default layout.',
                                                'inertia' => 'React/Vue layout component path (e.g., layouts/AuthenticatedLayout)',
                                                default => 'Your layout file or component. Leave empty to use FiltCMS default layout.',
                                            }),

                                        TextInput::make('app_layout_section')
                                            ->label('Content Section Name')
                                            ->placeholder('content')
                                            ->default('content')
                                            ->helperText('The @yield() section name in your layout (e.g., content, main, slot)')
                                            ->visible(fn ($get) => $get('app_layout_type') === 'blade'),

                                        TextInput::make('inertia_page_component')
                                            ->label('Inertia Page Component Path')
                                            ->placeholder('FiltCMS/Blog')
                                            ->helperText('The React/Vue component path for rendering (you need to create this component)')
                                            ->visible(fn ($get) => $get('app_layout_type') === 'inertia'),
                                    ])
                                    ->columns(2),

                                Section::make('Blog View Settings')
                                    ->description('Configure how blog posts are displayed on the frontend')
                                    ->schema([
                                        Select::make('blog_index_view_type')
                                            ->label('All Blogs View Type')
                                            ->options([
                                                'default' => 'Default (Uses App Layout)',
                                                'file' => 'Custom View File',
                                                'custom' => 'Custom HTML/CSS/JS',
                                            ])
                                            ->default('default')
                                            ->live()
                                            ->required(),

                                        TextInput::make('blog_index_view_file')
                                            ->label('All Blogs View File')
                                            ->required()
                                            ->placeholder('e.g., blog.index or custom-blog-list')
                                            ->helperText('Enter the full view file name (without .blade.php)')
                                            ->visible(fn ($get) => $get('blog_index_view_type') === 'file'),

                                        CodeEditor::make('blog_index_custom_html')
                                            ->label('Custom HTML')
                                            ->language(Language::Html)
                                            ->helperText('Use @foreach($blogs as $blog) ... @endforeach to loop through blogs')
                                            ->visible(fn ($get) => $get('blog_index_view_type') === 'custom')
                                            ->columnSpanFull(),

                                        CodeEditor::make('blog_index_custom_css')
                                            ->label('Custom CSS')
                                            ->language(Language::Css)
                                            ->visible(fn ($get) => $get('blog_index_view_type') === 'custom')
                                            ->columnSpanFull(),

                                        CodeEditor::make('blog_index_custom_js')
                                            ->label('Custom JavaScript')
                                            ->language(Language::JavaScript)
                                            ->visible(fn ($get) => $get('blog_index_view_type') === 'custom')
                                            ->columnSpanFull(),

                                        Select::make('blog_show_view_type')
                                            ->label('Blog Detail View Type')
                                            ->options([
                                                'default' => 'Default (Uses App Layout)',
                                                'file' => 'Custom View File',
                                                'custom' => 'Custom HTML/CSS/JS',
                                            ])
                                            ->default('default')
                                            ->live()
                                            ->required(),

                                        TextInput::make('blog_show_view_file')
                                            ->label('Blog Detail View File')
                                            ->required()
                                            ->placeholder('e.g., blog.show or custom-blog-detail')
                                            ->helperText('Enter the full view file name (without .blade.php)')
                                            ->visible(fn ($get) => $get('blog_show_view_type') === 'file'),

                                        CodeEditor::make('blog_show_custom_html')
                                            ->label('Custom HTML')
                                            ->language(Language::Html)
                                            ->helperText('Use {{ $blog->title }}, {{ $blog->content }}, {{ $blog->featured_image }}, etc.')
                                            ->visible(fn ($get) => $get('blog_show_view_type') === 'custom')
                                            ->columnSpanFull(),

                                        CodeEditor::make('blog_show_custom_css')
                                            ->label('Custom CSS')
                                            ->language(Language::Css)
                                            ->visible(fn ($get) => $get('blog_show_view_type') === 'custom')
                                            ->columnSpanFull(),

                                        CodeEditor::make('blog_show_custom_js')
                                            ->label('Custom JavaScript')
                                            ->language(Language::JavaScript)
                                            ->visible(fn ($get) => $get('blog_show_view_type') === 'custom')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->collapsible(),

                                Section::make('Page View Settings')
                                    ->description('Configure how pages are displayed on the frontend')
                                    ->schema([
                                        Select::make('page_show_view_type')
                                            ->label('Page View Type')
                                            ->options([
                                                'default' => 'Default (Uses App Layout)',
                                                'file' => 'Custom View File',
                                                'custom' => 'Custom HTML/CSS/JS',
                                            ])
                                            ->default('default')
                                            ->live()
                                            ->required(),

                                        TextInput::make('page_show_view_file')
                                            ->label('Page View File')
                                            ->required()
                                            ->placeholder('e.g., pages.show or custom-page')
                                            ->helperText('Enter the full view file name (without .blade.php)')
                                            ->visible(fn ($get) => $get('page_show_view_type') === 'file'),

                                        CodeEditor::make('page_show_custom_html')
                                            ->label('Custom HTML')
                                            ->language(Language::Html)
                                            ->helperText('Use {{ $page->title }}, {{ $page->content }}, etc.')
                                            ->visible(fn ($get) => $get('page_show_view_type') === 'custom')
                                            ->columnSpanFull(),

                                        CodeEditor::make('page_show_custom_css')
                                            ->label('Custom CSS')
                                            ->language(Language::Css)
                                            ->visible(fn ($get) => $get('page_show_view_type') === 'custom')
                                            ->columnSpanFull(),

                                        CodeEditor::make('page_show_custom_js')
                                            ->label('Custom JavaScript')
                                            ->language(Language::JavaScript)
                                            ->visible(fn ($get) => $get('page_show_view_type') === 'custom')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->collapsible(),
                            ]),

                        Tab::make('SEO')
                            ->schema([
                                Section::make('Default Meta Tags')
                                    ->schema([
                                        TextInput::make('default_meta_title')
                                            ->label('Default Meta Title')
                                            ->maxLength(60)
                                            ->helperText('Used when page doesn\'t have a custom meta title'),

                                        Textarea::make('default_meta_description')
                                            ->label('Default Meta Description')
                                            ->rows(3)
                                            ->maxLength(160)
                                            ->helperText('Used when page doesn\'t have a custom meta description'),

                                        Textarea::make('default_meta_keywords')
                                            ->label('Default Meta Keywords')
                                            ->rows(2)
                                            ->helperText('Comma-separated keywords'),
                                    ]),
                            ]),

                        Tab::make('Social Media')
                            ->schema([
                                Section::make('Social Media Links')
                                    ->schema([
                                        TextInput::make('facebook_url')
                                            ->label('Facebook URL')
                                            ->url()
                                            ->prefix('https://'),

                                        TextInput::make('twitter_url')
                                            ->label('Twitter URL')
                                            ->url()
                                            ->prefix('https://'),

                                        TextInput::make('instagram_url')
                                            ->label('Instagram URL')
                                            ->url()
                                            ->prefix('https://'),

                                        TextInput::make('linkedin_url')
                                            ->label('LinkedIn URL')
                                            ->url()
                                            ->prefix('https://'),

                                        Toggle::make('enable_share_buttons')
                                            ->label('Enable Share Buttons')
                                            ->helperText('Show social media share buttons on blog posts')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),

                        Tab::make('Notifications')
                            ->schema([
                                Section::make('Email Notifications')
                                    ->schema([
                                        Toggle::make('email_notifications')
                                            ->label('Enable Email Notifications')
                                            ->helperText('Send email notifications for various events'),

                                        TextInput::make('notification_email')
                                            ->label('Notification Email')
                                            ->email()
                                            ->helperText('Email address to receive notifications'),
                                    ]),
                            ]),

                        Tab::make('Advanced')
                            ->schema([
                                Section::make('Custom Code')
                                    ->schema([
                                        Textarea::make('custom_css')
                                            ->label('Custom CSS')
                                            ->rows(10)
                                            ->helperText('Add custom CSS for blog and pages')
                                            ->columnSpanFull(),

                                        Textarea::make('custom_js')
                                            ->label('Custom JavaScript')
                                            ->rows(10)
                                            ->helperText('Add custom JavaScript for blog and pages')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            $type = $this->getSettingType($key, $value);
            Setting::set($key, $value, $type);
        }

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }

    protected function getSettingType(string $key, $value): string
    {
        if (is_bool($value)) {
            return 'boolean';
        }

        if (is_int($value)) {
            return 'integer';
        }

        if (is_array($value)) {
            return 'array';
        }

        return 'string';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->submit('save'),
        ];
    }
}
