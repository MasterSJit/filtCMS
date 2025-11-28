<?php

namespace EthickS\FiltCMS\Pages;

use BackedEnum;
use EthickS\FiltCMS\Models\Setting;
use Filament\Actions\Action;
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
