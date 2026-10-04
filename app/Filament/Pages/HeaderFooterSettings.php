<?php

namespace App\Filament\Pages;

use App\Models\Page;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page as FilamentPage;

class HeaderFooterSettings extends FilamentPage
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Header & Footer';

    protected static ?int $navigationSort = 21;

    protected static string $view = 'filament.pages.header-footer-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            // Header Settings
            'topbar_enabled'           => (bool) Setting::get('topbar_enabled', true),
            'topbar_custom_text'       => Setting::get('topbar_custom_text', ''),
            'theme_topbar_bg'          => Setting::get('theme_topbar_bg', '#000000'),
            'theme_topbar_text'        => Setting::get('theme_topbar_text', '#ffffff'),
            'site_name'                => Setting::get('site_name', 'Masala Valley'),
            'tab_title'                => Setting::get('tab_title', 'Masala Valley — Pure, Organic & Halal'),
            'site_tagline'             => Setting::get('site_tagline', 'Pure, Organic & Halal'),
            'site_logo'                => Setting::get('site_logo'),
            'site_favicon'             => Setting::get('site_favicon'),
            'site_logo_height'         => Setting::get('site_logo_height', '52'),
            'search_placeholder'       => Setting::get('search_placeholder', 'Search honey, dates, ghee, spices...'),
            'header_show_track'        => (bool) Setting::get('header_show_track', true),
            'header_show_account'      => (bool) Setting::get('header_show_account', true),
            'header_show_wishlist'     => (bool) Setting::get('header_show_wishlist', true),
            'header_show_cart'         => (bool) Setting::get('header_show_cart', true),

            // Footer Settings
            'footer_about'             => Setting::get('footer_about', 'Pure, organic & halal groceries — honey, dates, ghee, spices and more, delivered across Bangladesh.'),
            'contact_phone'            => Setting::get('contact_phone', ''),
            'contact_email'            => Setting::get('contact_email', ''),
            'contact_address'          => Setting::get('contact_address', ''),
            'social_facebook'          => Setting::get('social_facebook', ''),
            'social_instagram'         => Setting::get('social_instagram', ''),
            'social_youtube'           => Setting::get('social_youtube', ''),
            'social_whatsapp'          => Setting::get('social_whatsapp', ''),
            'footer_show_apps'         => (bool) Setting::get('footer_show_apps', true),
            'app_play_store_url'       => Setting::get('app_play_store_url', ''),
            'app_store_url'            => Setting::get('app_store_url', ''),
            'footer_col1_title'        => Setting::get('footer_col1_title', 'Information'),
            'footer_col2_title'        => Setting::get('footer_col2_title', 'Support'),
            'footer_col3_title'        => Setting::get('footer_col3_title', 'Consumer Policy'),
            'footer_text'              => Setting::get('footer_text', '© ' . date('Y') . ' Masala Valley. All rights reserved.'),
            'footer_show_payment_methods' => (bool) Setting::get('footer_show_payment_methods', true),
            'theme_footer_bg'          => Setting::get('theme_footer_bg', '#fbf8f3'),
            'theme_footer_text_color'  => Setting::get('theme_footer_text_color', '#1e293b'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('SettingsTabs')
                    ->tabs([
                        // ── TAB 1: HEADER ──
                        Forms\Components\Tabs\Tab::make('Header Customizer')
                            ->icon('heroicon-o-bars-3-center-left')
                            ->schema([
                                Forms\Components\Section::make('Top Announcement Bar')
                                    ->description('Customize the announcement banner shown at the very top of your site')
                                    ->schema([
                                        Forms\Components\Toggle::make('topbar_enabled')
                                            ->label('Enable Topbar Announcement')
                                            ->default(true),

                                        Forms\Components\TextInput::make('topbar_custom_text')
                                            ->label('Custom Announcement Text')
                                            ->helperText('Leave empty to auto-display free shipping & gift rewards based on General Settings.')
                                            ->placeholder('Free delivery over ৳1,500 in Dhaka • Cash on delivery available • 100% Organic Guarantee')
                                            ->columnSpanFull(),

                                        Forms\Components\ColorPicker::make('theme_topbar_bg')
                                            ->label('Topbar Background Color'),

                                        Forms\Components\ColorPicker::make('theme_topbar_text')
                                            ->label('Topbar Text Color'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Logo & Identity')
                                    ->schema([
                                        Forms\Components\TextInput::make('site_name')
                                            ->label('Brand / Site Name')
                                            ->required()
                                            ->placeholder('Masala Valley'),

                                        Forms\Components\TextInput::make('tab_title')
                                            ->label('Browser Tab Title (Website Title)')
                                            ->helperText('Text displayed on the browser tab (Chrome/Firefox/Safari).')
                                            ->placeholder('Masala Valley — Pure, Organic & Halal'),

                                        Forms\Components\TextInput::make('site_tagline')
                                            ->label('Tagline / Slogan')
                                            ->placeholder('Pure, Organic & Halal'),

                                        Forms\Components\Select::make('site_logo_height')
                                            ->label('Header Logo Height')
                                            ->options([
                                                '38' => '38px (Compact)',
                                                '45' => '45px (Medium)',
                                                '52' => '52px (Standard — Recommended)',
                                                '60' => '60px (Large)',
                                                '70' => '70px (Extra Large)',
                                                '80' => '80px (Jumbo)',
                                            ])->default('52'),

                                        Forms\Components\FileUpload::make('site_logo')
                                            ->label('Site Logo')
                                            ->disk('public')
                                            ->directory('brand')
                                            ->image()
                                            ->visibility('public')
                                            ->maxSize(10240),

                                        Forms\Components\FileUpload::make('site_favicon')
                                            ->label('Favicon')
                                            ->disk('public')
                                            ->directory('brand')
                                            ->image()
                                            ->visibility('public')
                                            ->maxSize(5120)
                                            ->acceptedFileTypes(['image/x-icon', 'image/png', 'image/svg+xml']),
                                    ])->columns(2),

                                Forms\Components\Section::make('Search Bar & Action Buttons')
                                    ->schema([
                                        Forms\Components\TextInput::make('search_placeholder')
                                            ->label('Search Placeholder Text')
                                            ->placeholder('Search honey, dates, ghee, spices...')
                                            ->columnSpanFull(),

                                        Forms\Components\Toggle::make('header_show_track')
                                            ->label('Show Track Order Button')
                                            ->default(true),

                                        Forms\Components\Toggle::make('header_show_account')
                                            ->label('Show Account / Sign In Button')
                                            ->default(true),

                                        Forms\Components\Toggle::make('header_show_wishlist')
                                            ->label('Show Wishlist Button')
                                            ->default(true),

                                        Forms\Components\Toggle::make('header_show_cart')
                                            ->label('Show Cart Button')
                                            ->default(true),
                                    ])->columns(4),
                            ]),

                        // ── TAB 2: FOOTER ──
                        Forms\Components\Tabs\Tab::make('Footer Customizer')
                            ->icon('heroicon-o-queue-list')
                            ->schema([
                                Forms\Components\Section::make('Footer Brand & About')
                                    ->schema([
                                        Forms\Components\Textarea::make('footer_about')
                                            ->label('About Us Summary (Footer Column 1)')
                                            ->rows(3)
                                            ->placeholder('Pure, organic & halal groceries delivered across Bangladesh.')
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make('contact_phone')
                                            ->label('Hotline / Phone Number')
                                            ->placeholder('01700000000'),

                                        Forms\Components\TextInput::make('contact_email')
                                            ->label('Contact Email')
                                            ->placeholder('support@masalavalley.com'),

                                        Forms\Components\TextInput::make('contact_address')
                                            ->label('Office / Store Address')
                                            ->placeholder('House 12, Road 4, Dhanmondi, Dhaka')
                                            ->columnSpanFull(),
                                    ])->columns(2),

                                Forms\Components\Section::make('Social Media Links')
                                    ->schema([
                                        Forms\Components\TextInput::make('social_facebook')
                                            ->label('Facebook Page URL')
                                            ->placeholder('https://facebook.com/masalavalley'),

                                        Forms\Components\TextInput::make('social_instagram')
                                            ->label('Instagram URL')
                                            ->placeholder('https://instagram.com/masalavalley'),

                                        Forms\Components\TextInput::make('social_whatsapp')
                                            ->label('WhatsApp Number (with country code)')
                                            ->placeholder('+8801700000000'),

                                        Forms\Components\TextInput::make('social_youtube')
                                            ->label('YouTube Channel URL')
                                            ->placeholder('https://youtube.com/@masalavalley'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Footer Columns Titles')
                                    ->description('Customize the header titles for the footer navigation columns')
                                    ->schema([
                                        Forms\Components\TextInput::make('footer_col1_title')
                                            ->label('Column 1 Title')
                                            ->default('Information'),

                                        Forms\Components\TextInput::make('footer_col2_title')
                                            ->label('Column 2 Title')
                                            ->default('Support'),

                                        Forms\Components\TextInput::make('footer_col3_title')
                                            ->label('Column 3 Title')
                                            ->default('Consumer Policy'),
                                    ])->columns(3),

                                Forms\Components\Section::make('App Store Badges & Payment Methods')
                                    ->schema([
                                        Forms\Components\Toggle::make('footer_show_apps')
                                            ->label('Show Google Play & App Store Badges')
                                            ->default(true),

                                        Forms\Components\TextInput::make('app_play_store_url')
                                            ->label('Google Play Store URL')
                                            ->placeholder('https://play.google.com/store/apps/...'),

                                        Forms\Components\TextInput::make('app_store_url')
                                            ->label('Apple App Store URL')
                                            ->placeholder('https://apps.apple.com/app/...'),

                                        Forms\Components\Toggle::make('footer_show_payment_methods')
                                            ->label('Show Payment Method Badges (bKash, Nagad, Visa, etc.)')
                                            ->default(true),

                                        Forms\Components\TextInput::make('footer_text')
                                            ->label('Copyright Notice')
                                            ->placeholder('© 2026 Masala Valley. All rights reserved.')
                                            ->columnSpanFull(),
                                    ])->columns(3),

                                Forms\Components\Section::make('Footer Colors')
                                    ->schema([
                                        Forms\Components\ColorPicker::make('theme_footer_bg')
                                            ->label('Footer Background Color'),

                                        Forms\Components\ColorPicker::make('theme_footer_text_color')
                                            ->label('Footer Text Color'),
                                    ])->columns(2),
                            ]),

                        // ── TAB 3: FOOTER PAGES (CMS) ──
                        Forms\Components\Tabs\Tab::make('Footer CMS Pages')
                            ->icon('heroicon-o-document-duplicate')
                            ->schema([
                                Forms\Components\Placeholder::make('cms_pages_info')
                                    ->label('Manage Dynamic Footer Pages')
                                    ->content('All pages linked in your website footer can be edited with your custom text, policies, and headings. Click "Edit in CMS" on any page below to modify its content, or click "Create New Page" to add custom pages.'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function getFooterPages(): array
    {
        $slugs = [
            'about'           => ['title' => 'About Us', 'column' => 'Information'],
            'terms'           => ['title' => 'Terms & Conditions', 'column' => 'Information'],
            'privacy'         => ['title' => 'Privacy Policy', 'column' => 'Information'],
            'careers'         => ['title' => 'Careers', 'column' => 'Information'],
            'support-center'  => ['title' => 'Support Center', 'column' => 'Support'],
            'how-to-order'    => ['title' => 'How to Order', 'column' => 'Support'],
            'payment'         => ['title' => 'Payment Methods', 'column' => 'Support'],
            'shipping'        => ['title' => 'Shipping & Delivery', 'column' => 'Support'],
            'happy-return'    => ['title' => 'Happy Return Policy', 'column' => 'Consumer Policy'],
            'refund-policy'   => ['title' => 'Refund Policy', 'column' => 'Consumer Policy'],
            'exchange'        => ['title' => 'Exchange Policy', 'column' => 'Consumer Policy'],
            'cancellation'    => ['title' => 'Cancellation Policy', 'column' => 'Consumer Policy'],
            'pre-order'       => ['title' => 'Pre-Order Policy', 'column' => 'Consumer Policy'],
            'extra-discount'  => ['title' => 'Extra Discount & Offers', 'column' => 'Consumer Policy'],
        ];

        $pages = Page::whereIn('slug', array_keys($slugs))->get()->keyBy('slug');

        $result = [];
        foreach ($slugs as $slug => $meta) {
            $p = $pages->get($slug);
            $result[] = [
                'slug'         => $slug,
                'title'        => $p ? $p->title : $meta['title'],
                'column'       => $meta['column'],
                'is_published' => $p ? $p->is_published : false,
                'id'           => $p?->id,
                'live_url'     => in_array($slug, ['about', 'privacy', 'terms']) ? url('/' . $slug) : url('/page/' . $slug),
                'edit_url'     => $p ? url('/admin/pages/' . $p->id . '/edit') : url('/admin/pages/create?slug=' . $slug . '&title=' . urlencode($meta['title'])),
            ];
        }

        return $result;
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $keys = [
            'topbar_enabled', 'topbar_custom_text', 'theme_topbar_bg', 'theme_topbar_text',
            'site_name', 'tab_title', 'site_tagline', 'site_logo', 'site_favicon', 'site_logo_height',
            'search_placeholder', 'header_show_track', 'header_show_account', 'header_show_wishlist', 'header_show_cart',
            'footer_about', 'contact_phone', 'contact_email', 'contact_address',
            'social_facebook', 'social_instagram', 'social_youtube', 'social_whatsapp',
            'footer_show_apps', 'app_play_store_url', 'app_store_url',
            'footer_col1_title', 'footer_col2_title', 'footer_col3_title',
            'footer_text', 'footer_show_payment_methods', 'theme_footer_bg', 'theme_footer_text_color',
        ];

        foreach ($keys as $key) {
            if (array_key_exists($key, $state)) {
                Setting::set($key, $state[$key]);
            }
        }

        // Keep meta_title synced with tab_title
        if (! empty($state['tab_title'])) {
            Setting::set('meta_title', $state['tab_title']);
        }

        // Clear view caches
        \Illuminate\Support\Facades\Cache::forget('storefront.home_view_data_v2');

        Notification::make()
            ->title('Header & Footer settings saved successfully!')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('pages_list')
                ->label('View All CMS Pages')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(url('/admin/pages')),

            \Filament\Actions\Action::make('create_page')
                ->label('Create New Page')
                ->icon('heroicon-o-plus')
                ->color('info')
                ->url(url('/admin/pages/create')),

            \Filament\Actions\Action::make('save')
                ->label('Save Changes')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action('save'),
        ];
    }
}
