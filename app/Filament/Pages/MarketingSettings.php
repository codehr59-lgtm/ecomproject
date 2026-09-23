<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class MarketingSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Marketing & SEO';
    protected static ?string $title           = 'Marketing & SEO Settings';
    protected static string  $view            = 'filament.pages.payment-settings';
    protected static ?int    $navigationSort  = 14;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'fb_pixel_id'       => Setting::get('fb_pixel_id', ''),
            'tiktok_pixel_id'   => Setting::get('tiktok_pixel_id', ''),
            'ga_id'             => Setting::get('ga_id', ''),
            'gtm_id'            => Setting::get('gtm_id', ''),
            'meta_title'        => Setting::get('meta_title', ''),
            'meta_description'  => Setting::get('meta_description', ''),
            'meta_keywords'     => Setting::get('meta_keywords', ''),
            'custom_head_code'  => Setting::get('custom_head_code', ''),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Facebook Pixel')
                    ->description('Add your Facebook Pixel ID to track conversions and build audiences.')
                    ->icon('heroicon-o-cursor-arrow-ripple')
                    ->schema([
                        TextInput::make('fb_pixel_id')
                            ->label('Pixel ID')
                            ->placeholder('e.g. 123456789012345')
                            ->helperText('Find this in Facebook Events Manager → Data Sources → Your Pixel.')
                            ->maxLength(50),
                    ]),

                Section::make('TikTok Pixel')
                    ->description('Add your TikTok Pixel ID to track conversions from TikTok ads.')
                    ->icon('heroicon-o-video-camera')
                    ->schema([
                        TextInput::make('tiktok_pixel_id')
                            ->label('Pixel ID')
                            ->placeholder('e.g. CXXXXXXXXXXXXXXXXX')
                            ->helperText('Find this in TikTok Ads Manager → Assets → Events → Web Events → Manage → Pixel ID.')
                            ->maxLength(50),
                    ]),

                Section::make('Google Analytics')
                    ->description('Add your Google Analytics or Google Tag Manager ID.')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        TextInput::make('ga_id')
                            ->label('GA4 Measurement ID')
                            ->placeholder('e.g. G-XXXXXXXXXX')
                            ->helperText('Find this in Google Analytics → Admin → Data Streams.')
                            ->maxLength(50),

                        TextInput::make('gtm_id')
                            ->label('GTM Container ID (optional)')
                            ->placeholder('e.g. GTM-XXXXXXX')
                            ->helperText('If you use Google Tag Manager instead of direct GA4.')
                            ->maxLength(50),
                    ]),

                Section::make('SEO Defaults')
                    ->description('Default meta tags for pages that don\'t have their own.')
                    ->icon('heroicon-o-magnifying-glass')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Default Meta Title')
                            ->placeholder('Shuvo — Pure, Organic & Halal')
                            ->maxLength(70),

                        Textarea::make('meta_description')
                            ->label('Default Meta Description')
                            ->placeholder('Shop pure, organic & halal products...')
                            ->rows(2)
                            ->maxLength(160),

                        TextInput::make('meta_keywords')
                            ->label('Meta Keywords')
                            ->placeholder('organic, halal, pure, bangladesh, grocery')
                            ->helperText('Comma-separated keywords.')
                            ->maxLength(255),
                    ]),

                Section::make('Custom Head Code')
                    ->description('Add any custom scripts to the <head> section of all frontend pages (e.g. chat widgets, verification tags).')
                    ->icon('heroicon-o-code-bracket')
                    ->schema([
                        Textarea::make('custom_head_code')
                            ->label('Custom HTML/JS')
                            ->placeholder('<script>...</script>')
                            ->rows(4)
                            ->maxLength(5000),
                    ])
                    ->collapsible()
                    ->collapsed(),

            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        Setting::set('fb_pixel_id',      (string) ($state['fb_pixel_id'] ?? ''));
        Setting::set('tiktok_pixel_id', (string) ($state['tiktok_pixel_id'] ?? ''));
        Setting::set('ga_id',            (string) ($state['ga_id'] ?? ''));
        Setting::set('gtm_id',           (string) ($state['gtm_id'] ?? ''));
        Setting::set('meta_title',       (string) ($state['meta_title'] ?? ''));
        Setting::set('meta_description', (string) ($state['meta_description'] ?? ''));
        Setting::set('meta_keywords',    (string) ($state['meta_keywords'] ?? ''));
        Setting::set('custom_head_code', (string) ($state['custom_head_code'] ?? ''));

        Notification::make()
            ->title('Marketing & SEO settings saved successfully.')
            ->success()
            ->send();
    }
}
