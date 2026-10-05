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

            // Floating Quick Contact Settings
            'floating_contact_enabled'        => (bool) Setting::get('floating_contact_enabled', true),
            'floating_contact_position'       => Setting::get('floating_contact_position', 'right'),
            'floating_contact_btn_bg'          => Setting::get('floating_contact_btn_bg', '#9B5123'),
            'floating_contact_btn_icon_color' => Setting::get('floating_contact_btn_icon_color', '#ffffff'),
            'floating_contact_badge'          => (bool) Setting::get('floating_contact_badge', true),
            'floating_contact_tooltip'        => Setting::get('floating_contact_tooltip', 'Need help? Contact us'),
            'floating_whatsapp_enabled'       => (bool) Setting::get('floating_whatsapp_enabled', true),
            'floating_whatsapp_number'        => Setting::get('floating_whatsapp_number', ''),
            'floating_whatsapp_message'       => Setting::get('floating_whatsapp_message', 'Hello Masala Valley, I want to inquire about a product.'),
            'floating_whatsapp_label'         => Setting::get('floating_whatsapp_label', 'WhatsApp'),
            'floating_messenger_enabled'      => (bool) Setting::get('floating_messenger_enabled', true),
            'floating_messenger_url'          => Setting::get('floating_messenger_url', ''),
            'floating_messenger_label'        => Setting::get('floating_messenger_label', 'Messenger'),
            'floating_phone_enabled'          => (bool) Setting::get('floating_phone_enabled', true),
            'floating_phone_number'           => Setting::get('floating_phone_number', ''),
            'floating_phone_label'            => Setting::get('floating_phone_label', 'Call Now'),
            'floating_email_enabled'          => (bool) Setting::get('floating_email_enabled', true),
            'floating_email_address'          => Setting::get('floating_email_address', ''),
            'floating_email_label'            => Setting::get('floating_email_label', 'Email Us'),

            // Contact Us Page Settings
            'contact_page_title'              => Setting::get('contact_page_title', 'Get in Touch'),
            'contact_page_subtitle'           => Setting::get('contact_page_subtitle', "Questions about an order, a product, or just want to say hello? We'd love to hear from you."),
            'contact_page_form_title'         => Setting::get('contact_page_form_title', 'Send us a message'),
            'contact_phone_timing'            => Setting::get('contact_phone_timing', 'Sat–Thu 9 am – 9 pm'),
            'contact_email_timing'            => Setting::get('contact_email_timing', 'We reply within 6 hours'),
            'contact_hours_1'                 => Setting::get('contact_hours_1', 'Sat – Thu: 9:00 am – 9:00 pm'),
            'contact_hours_2'                 => Setting::get('contact_hours_2', 'Friday: 2:00 pm – 8:00 pm'),
            'contact_map_label'               => Setting::get('contact_map_label', 'Rampura, Dhaka'),
            'contact_map_iframe'              => Setting::get('contact_map_iframe', ''),
            'contact_show_map'                => (bool) Setting::get('contact_show_map', true),
            'contact_show_social'             => (bool) Setting::get('contact_show_social', true),

            // Floating Cart Button Settings
            'floating_cart_enabled'           => (bool) Setting::get('floating_cart_enabled', true),
            'floating_cart_position'          => Setting::get('floating_cart_position', 'bottom_right'),
            'floating_cart_btn_bg'            => Setting::get('floating_cart_btn_bg', '#2e7d32'),
            'floating_cart_btn_text_color'    => Setting::get('floating_cart_btn_text_color', '#ffffff'),
            'floating_cart_show_price'        => (bool) Setting::get('floating_cart_show_price', true),
            'floating_cart_hide_empty'        => (bool) Setting::get('floating_cart_hide_empty', false),
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

                        // ── TAB 4: FLOATING CONTACT BUTTON ──
                        Forms\Components\Tabs\Tab::make('Floating Contact Widget')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                Forms\Components\Section::make('Floating Contact Button & Position')
                                    ->description('Configure the main sticky floating contact button (bottom right/left)')
                                    ->schema([
                                        Forms\Components\Toggle::make('floating_contact_enabled')
                                            ->label('Enable Floating Contact Button')
                                            ->helperText('Show the interactive floating contact widget on the storefront.')
                                            ->default(true),

                                        Forms\Components\Select::make('floating_contact_position')
                                            ->label('Screen Position')
                                            ->options([
                                                'right' => 'Bottom Right (Recommended)',
                                                'left'  => 'Bottom Left',
                                            ])
                                            ->default('right'),

                                        Forms\Components\ColorPicker::make('floating_contact_btn_bg')
                                            ->label('Main Button Color')
                                            ->default('#9B5123'),

                                        Forms\Components\ColorPicker::make('floating_contact_btn_icon_color')
                                            ->label('Main Button Icon Color')
                                            ->default('#ffffff'),

                                        Forms\Components\Toggle::make('floating_contact_badge')
                                            ->label('Show Online Pulse Badge (Blue Dot)')
                                            ->helperText('Displays the live active status indicator dot on the chat icon.')
                                            ->default(true),

                                        Forms\Components\TextInput::make('floating_contact_tooltip')
                                            ->label('Hover Tooltip Text')
                                            ->placeholder('Need help? Contact us')
                                            ->default('Need help? Contact us'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Communication Channels')
                                    ->description('Enable and customize each quick-contact channel in the popup menu (WhatsApp, Messenger, Phone, Email)')
                                    ->schema([
                                        // WhatsApp
                                        Forms\Components\Fieldset::make('WhatsApp')
                                            ->schema([
                                                Forms\Components\Toggle::make('floating_whatsapp_enabled')
                                                    ->label('Enable WhatsApp')
                                                    ->default(true),
                                                Forms\Components\TextInput::make('floating_whatsapp_label')
                                                    ->label('Button Label')
                                                    ->default('WhatsApp'),
                                                Forms\Components\TextInput::make('floating_whatsapp_number')
                                                    ->label('WhatsApp Number')
                                                    ->helperText('e.g. 01700000000 or +8801700000000 (falls back to Contact/Social WhatsApp).')
                                                    ->placeholder('01700000000'),
                                                Forms\Components\TextInput::make('floating_whatsapp_message')
                                                    ->label('Pre-filled Message')
                                                    ->placeholder('Hello Masala Valley, I want to inquire about a product.')
                                                    ->columnSpanFull(),
                                            ])->columns(3),

                                        // Messenger
                                        Forms\Components\Fieldset::make('Facebook Messenger')
                                            ->schema([
                                                Forms\Components\Toggle::make('floating_messenger_enabled')
                                                    ->label('Enable Messenger')
                                                    ->default(true),
                                                Forms\Components\TextInput::make('floating_messenger_label')
                                                    ->label('Button Label')
                                                    ->default('Messenger'),
                                                Forms\Components\TextInput::make('floating_messenger_url')
                                                    ->label('Messenger Link or Page Username')
                                                    ->helperText('e.g. masalavalley or https://m.me/masalavalley')
                                                    ->placeholder('masalavalley'),
                                            ])->columns(3),

                                        // Call / Phone
                                        Forms\Components\Fieldset::make('Direct Phone Call')
                                            ->schema([
                                                Forms\Components\Toggle::make('floating_phone_enabled')
                                                    ->label('Enable Direct Call')
                                                    ->default(true),
                                                Forms\Components\TextInput::make('floating_phone_label')
                                                    ->label('Button Label')
                                                    ->default('Call Now'),
                                                Forms\Components\TextInput::make('floating_phone_number')
                                                    ->label('Phone Number')
                                                    ->helperText('e.g. +8801700000000 (falls back to site contact phone).')
                                                    ->placeholder('+8801700000000'),
                                            ])->columns(3),

                                        // Email
                                        Forms\Components\Fieldset::make('Email Support')
                                            ->schema([
                                                Forms\Components\Toggle::make('floating_email_enabled')
                                                    ->label('Enable Email')
                                                    ->default(true),
                                                Forms\Components\TextInput::make('floating_email_label')
                                                    ->label('Button Label')
                                                    ->default('Email Us'),
                                                Forms\Components\TextInput::make('floating_email_address')
                                                    ->label('Support Email Address')
                                                    ->helperText('e.g. support@masalavalley.com (falls back to site contact email).')
                                                    ->placeholder('support@masalavalley.com'),
                                            ])->columns(3),
                                    ]),
                            ]),

                        // ── TAB 5: CONTACT PAGE ──
                        Forms\Components\Tabs\Tab::make('Contact Page')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                Forms\Components\Section::make('Page Header & Titles (যোগাযোগ পেজ হেডার)')
                                    ->description('Customize main titles and headings displayed on the /contact page')
                                    ->schema([
                                        Forms\Components\TextInput::make('contact_page_title')
                                            ->label('Page Title (পেজের শিরোনাম)')
                                            ->default('Get in Touch')
                                            ->required(),

                                        Forms\Components\TextInput::make('contact_page_form_title')
                                            ->label('Form Card Title (ফর্ম কার্ড শিরোনাম)')
                                            ->default('Send us a message')
                                            ->required(),

                                        Forms\Components\Textarea::make('contact_page_subtitle')
                                            ->label('Page Subtitle (উপশিরোনাম)')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ])->columns(2),

                                Forms\Components\Section::make('Store Contact Information (অফিস ও যোগাযোগের তথ্য)')
                                    ->description('These details are shown in the contact details card and can be updated anytime.')
                                    ->schema([
                                        Forms\Components\Textarea::make('contact_address')
                                            ->label('Office / Store Address (অফিস ঠিকানা)')
                                            ->placeholder('House 14, Road 5, Block B, Rampura, Dhaka 1219, Bangladesh')
                                            ->rows(2)
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make('contact_phone')
                                            ->label('Phone / WhatsApp Number (ফোন নম্বর)')
                                            ->placeholder('09642-XXXXXX'),

                                        Forms\Components\TextInput::make('contact_phone_timing')
                                            ->label('Phone Availability (ফোনের সময়সূচি)')
                                            ->placeholder('Sat–Thu 9 am – 9 pm'),

                                        Forms\Components\TextInput::make('contact_email')
                                            ->label('Support Email (ইমেইল ঠিকানা)')
                                            ->placeholder('hello@masalavalley.com'),

                                        Forms\Components\TextInput::make('contact_email_timing')
                                            ->label('Email Response Promise (ইমেইল রিপ্লাই সময়)')
                                            ->placeholder('We reply within 6 hours'),

                                        Forms\Components\TextInput::make('contact_hours_1')
                                            ->label('Business Hours - Line 1 (কার্যদিবস)')
                                            ->placeholder('Sat – Thu: 9:00 am – 9:00 pm'),

                                        Forms\Components\TextInput::make('contact_hours_2')
                                            ->label('Business Hours - Line 2 (ছুটির দিন/শুক্রবার)')
                                            ->placeholder('Friday: 2:00 pm – 8:00 pm'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Location Map & Social Links (ম্যাপ ও সোশ্যাল মিডিয়া)')
                                    ->schema([
                                        Forms\Components\Toggle::make('contact_show_map')
                                            ->label('Show Map Card on Contact Page')
                                            ->default(true),

                                        Forms\Components\TextInput::make('contact_map_label')
                                            ->label('Map Location Name')
                                            ->placeholder('Rampura, Dhaka')
                                            ->helperText('Shown on the map badge if no custom Google Maps iframe is provided'),

                                        Forms\Components\Textarea::make('contact_map_iframe')
                                            ->label('Google Maps Embed Code (iframe)')
                                            ->helperText('Paste <iframe> code from Google Maps > Share > Embed a map (optional)')
                                            ->placeholder('<iframe src="https://www.google.com/maps/embed?..." ...></iframe>')
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        Forms\Components\Toggle::make('contact_show_social')
                                            ->label('Show Social Media Links (Facebook, Instagram, WhatsApp)')
                                            ->default(true)
                                            ->columnSpanFull(),
                                    ])->columns(2),
                            ]),

                        // ── TAB 6: FLOATING CART BUTTON ──
                        Forms\Components\Tabs\Tab::make('Floating Cart Button')
                            ->icon('heroicon-o-shopping-bag')
                            ->schema([
                                Forms\Components\Section::make('Floating Cart Button (ভাসমান কার্ট বাটন)')
                                    ->description('A modern floating sticky shopping cart button on desktop and mobile so customers can quickly see cart items and checkout from any page.')
                                    ->schema([
                                        Forms\Components\Toggle::make('floating_cart_enabled')
                                            ->label('Enable Floating Cart Button (কার্ট বাটন অন/অফ)')
                                            ->helperText('Show the floating cart widget on mobile and desktop storefront.')
                                            ->default(true),

                                        Forms\Components\Select::make('floating_cart_position')
                                            ->label('Screen Position (স্ক্রিনের অবস্থান)')
                                            ->options([
                                                'bottom_right' => 'Bottom Right (Stacks cleanly above contact button)',
                                                'bottom_left'  => 'Bottom Left',
                                                'middle_right' => 'Middle Right (Sticky Side Tab)',
                                            ])
                                            ->default('bottom_right')
                                            ->required(),

                                        Forms\Components\ColorPicker::make('floating_cart_btn_bg')
                                            ->label('Button Background Color')
                                            ->default('#2e7d32'),

                                        Forms\Components\ColorPicker::make('floating_cart_btn_text_color')
                                            ->label('Button Text & Icon Color')
                                            ->default('#ffffff'),

                                        Forms\Components\Toggle::make('floating_cart_show_price')
                                            ->label('Show Total Price (৳ মূল্য দেখান)')
                                            ->helperText('Display total cart amount along with the item count.')
                                            ->default(true),

                                        Forms\Components\Toggle::make('floating_cart_hide_empty')
                                            ->label('Hide When Cart is Empty (কার্ট খালি থাকলে লুকান)')
                                            ->helperText('If enabled, the button only appears after at least 1 product is added.')
                                            ->default(false),
                                    ])->columns(2),
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
            'floating_contact_enabled', 'floating_contact_position', 'floating_contact_btn_bg',
            'floating_contact_btn_icon_color', 'floating_contact_badge', 'floating_contact_tooltip',
            'floating_whatsapp_enabled', 'floating_whatsapp_number', 'floating_whatsapp_message', 'floating_whatsapp_label',
            'floating_messenger_enabled', 'floating_messenger_url', 'floating_messenger_label',
            'floating_phone_enabled', 'floating_phone_number', 'floating_phone_label',
            'floating_email_enabled', 'floating_email_address', 'floating_email_label',
            'contact_page_title', 'contact_page_subtitle', 'contact_page_form_title',
            'contact_phone_timing', 'contact_email_timing', 'contact_hours_1', 'contact_hours_2',
            'contact_map_label', 'contact_map_iframe', 'contact_show_map', 'contact_show_social',
            'floating_cart_enabled', 'floating_cart_position', 'floating_cart_btn_bg',
            'floating_cart_btn_text_color', 'floating_cart_show_price', 'floating_cart_hide_empty',
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
