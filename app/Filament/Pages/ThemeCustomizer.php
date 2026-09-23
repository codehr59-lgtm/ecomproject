<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ThemeCustomizer extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationLabel = 'Theme Customizer';

    protected static ?int $navigationSort = 35;

    protected static string $view = 'filament.pages.theme-customizer';

    public ?array $data = [];

    private function themeKeys(): array
    {
        return [
            'site_name'        => ['default' => 'Shuvo'],
            'site_tagline'     => ['default' => 'Pure, Organic & Halal'],
            'site_logo'        => [],
            'site_favicon'     => [],
            'site_logo_height' => ['default' => '52'],
            // brand
            'primary_color'    => ['key' => 'theme_primary_color'],
            'secondary_color'  => ['key' => 'theme_secondary_color'],
            'accent_color'     => ['key' => 'theme_accent_color'],
            // topbar
            'topbar_bg'        => ['key' => 'theme_topbar_bg'],
            'topbar_bg_hover'  => ['key' => 'theme_topbar_bg_hover'],
            'topbar_text'      => ['key' => 'theme_topbar_text'],
            // header action buttons visibility
            'header_show_track'    => ['default' => true],
            'header_show_account'  => ['default' => true],
            'header_show_wishlist' => ['default' => true],
            'header_show_cart'     => ['default' => true],
            // cart drawer & free gift promotion
            'enable_free_gift'     => ['default' => true],
            'free_gift_min'        => ['default' => '3000'],
            'free_gift_name'       => ['default' => 'free Lychee Honey sachet'],
            'free_gift_success_msg'=> ['default' => "🎉 You've unlocked a free gift! It'll be added at checkout."],
            // header colors
            'header_bg'              => ['key' => 'theme_header_bg'],
            'header_icon_color'      => ['key' => 'theme_header_icon_color'],
            'header_icon_hover'      => ['key' => 'theme_header_icon_hover'],
            'header_icon_bg_hover'   => ['key' => 'theme_header_icon_bg_hover'],
            'header_badge_bg'        => ['key' => 'theme_header_badge_bg'],
            'header_badge_text'      => ['key' => 'theme_header_badge_text'],
            // menu / category nav
            'menu_bg'          => ['key' => 'theme_menu_bg'],
            'nav_bg'           => ['key' => 'theme_nav_bg'],
            'nav_bg_hover'     => ['key' => 'theme_nav_bg_hover'],
            'nav_text'         => ['key' => 'theme_nav_text'],
            'nav_link_text'    => ['key' => 'theme_nav_link_text'],
            'nav_link_hover_text'  => ['key' => 'theme_nav_link_hover_text'],
            'nav_link_hover_bg'    => ['key' => 'theme_nav_link_hover_bg'],
            // primary button
            'btn_bg'           => ['key' => 'theme_btn_bg'],
            'btn_bg_hover'     => ['key' => 'theme_btn_bg_hover'],
            'btn_text'         => ['key' => 'theme_btn_text'],
            'btn_text_hover'   => ['key' => 'theme_btn_text_hover'],
            // add to cart
            'addcart_bg'       => ['key' => 'theme_addcart_bg'],
            'addcart_bg_hover' => ['key' => 'theme_addcart_bg_hover'],
            'addcart_text'     => ['key' => 'theme_addcart_text'],
            'addcart_text_hover' => ['key' => 'theme_addcart_text_hover'],
            // buy now
            'buynow_bg'        => ['key' => 'theme_buynow_bg'],
            'buynow_bg_hover'  => ['key' => 'theme_buynow_bg_hover'],
            'buynow_text'      => ['key' => 'theme_buynow_text'],
            'buynow_text_hover'=> ['key' => 'theme_buynow_text_hover'],
            // slider / CTA
            'slider_bg'        => ['key' => 'theme_slider_bg'],
            'slider_bg_hover'  => ['key' => 'theme_slider_bg_hover'],
            'slider_text'      => ['key' => 'theme_slider_text'],
            'slider_text_hover'=> ['key' => 'theme_slider_text_hover'],
            // link
            'link_color'       => ['key' => 'theme_link_color'],
            'link_hover'       => ['key' => 'theme_link_hover'],
            // text
            'text_color'       => ['key' => 'theme_text_color'],
            'text_muted'       => ['key' => 'theme_text_muted'],
            // footer
            'footer_bg'        => ['key' => 'theme_footer_bg'],
            'footer_text_color'=> ['key' => 'theme_footer_text_color'],
            'footer_link_hover'=> ['key' => 'theme_footer_link_hover'],
            // typography
            'font_heading'     => ['key' => 'theme_font_heading', 'default' => 'Bricolage Grotesque'],
            'font_body'        => ['key' => 'theme_font_body', 'default' => 'Hanken Grotesk'],
            'base_font_size'   => ['key' => 'theme_base_font_size', 'default' => '15'],
            'heading_scale'    => ['key' => 'theme_heading_scale', 'default' => '1'],
            // footer content
            'footer_text'      => ['default' => '© ' . date('Y') . ' Shuvo. All rights reserved.'],
            'footer_about'     => [],
            // social
            'social_facebook'  => [],
            'social_instagram' => [],
            'social_youtube'   => [],
            'social_whatsapp'  => [],
            // contact
            'contact_phone'    => [],
            'contact_email'    => [],
            'contact_address'  => [],
            // mobile header
            'mob_search_bg'     => ['key' => 'theme_mob_search_bg'],
            'mob_search_text'   => ['key' => 'theme_mob_search_text'],
            'mob_cart_bg'       => ['key' => 'theme_mob_cart_bg'],
            'mob_cart_text'     => ['key' => 'theme_mob_cart_text'],
            'mob_tab_text'      => ['key' => 'theme_mob_tab_text'],
            'mob_tab_active'    => ['key' => 'theme_mob_tab_active'],
            // mobile bottom nav
            'mobnav_bg'         => ['key' => 'theme_mobnav_bg'],
            'mobnav_text'       => ['key' => 'theme_mobnav_text'],
            'mobnav_active'     => ['key' => 'theme_mobnav_active'],
            'mobnav_home'       => ['default' => '1'],
            'mobnav_categories' => ['default' => '0'],
            'mobnav_shop'       => ['default' => '1'],
            'mobnav_cart'       => ['default' => '1'],
            'mobnav_wishlist'   => ['default' => '1'],
            'mobnav_account'    => ['default' => '1'],
        ];
    }

    public function mount(): void
    {
        $fill = [];
        $allSettings = \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key')->toArray();
        foreach ($this->themeKeys() as $form => $opts) {
            $dbKey = $opts['key'] ?? $form;
            if (array_key_exists($dbKey, $allSettings)) {
                $raw = $allSettings[$dbKey];
                if (is_bool($opts['default'] ?? null)) {
                    $fill[$form] = filter_var($raw, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? (bool) $raw;
                } else {
                    $fill[$form] = $raw ?? '';
                }
            } else {
                $fill[$form] = $opts['default'] ?? null;
            }
        }
        $this->form->fill($fill);
    }

    public function form(Form $form): Form
    {
        $cp = fn (string $name, string $label) => Forms\Components\ColorPicker::make($name)->label($label);

        return $form
            ->schema([
                Forms\Components\Section::make('Site Identity')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')->label('Site Name')->maxLength(100),
                        Forms\Components\TextInput::make('site_tagline')->label('Tagline')->maxLength(200),
                        Forms\Components\FileUpload::make('site_logo')->label('Site Logo')->image()->directory('brand')->visibility('public')->maxSize(10240),
                        Forms\Components\FileUpload::make('site_favicon')->label('Favicon')->image()->directory('brand')->visibility('public')->maxSize(5120)
                            ->acceptedFileTypes(['image/x-icon', 'image/png', 'image/svg+xml']),
                        Forms\Components\Select::make('site_logo_height')
                            ->label('Header Logo Height')
                            ->options([
                                '38' => '38px (Compact)',
                                '45' => '45px (Medium)',
                                '52' => '52px (Standard — Recommended)',
                                '60' => '60px (Large)',
                                '70' => '70px (Extra Large)',
                                '80' => '80px (Jumbo)',
                            ])
                            ->default('52')
                            ->helperText('Display height of your logo in the header search row'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Brand Colors')
                    ->description('Global brand palette — affects links, badges, and default accents')
                    ->schema([
                        $cp('primary_color', 'Primary Color'),
                        $cp('secondary_color', 'Secondary Color'),
                        $cp('accent_color', 'Accent Color'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Top Bar (Announcement)')
                    ->schema([
                        $cp('topbar_bg', 'BG'),
                        $cp('topbar_bg_hover', 'BG Hover'),
                        $cp('topbar_text', 'Text'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Header & Action Buttons')
                    ->description('Search bar section, action button visibility & color styling')
                    ->schema([
                        Forms\Components\Fieldset::make('Action Buttons Visibility (Show / Hide)')
                            ->schema([
                                Forms\Components\Toggle::make('header_show_track')
                                    ->label('Track Order (Truck)')
                                    ->helperText('Show/hide Track Order button')
                                    ->default(true),
                                Forms\Components\Toggle::make('header_show_account')
                                    ->label('Sign in / Account')
                                    ->helperText('Show/hide Account / Sign in button')
                                    ->default(true),
                                Forms\Components\Toggle::make('header_show_wishlist')
                                    ->label('Wishlist')
                                    ->helperText('Show/hide Wishlist button')
                                    ->default(true),
                                Forms\Components\Toggle::make('header_show_cart')
                                    ->label('Cart')
                                    ->helperText('Show/hide Cart button')
                                    ->default(true),
                            ])
                            ->columns(4),

                        Forms\Components\Fieldset::make('Header & Action Colors')
                            ->schema([
                                $cp('header_bg', 'Header Background'),
                                $cp('header_icon_color', 'Action Icons & Label Color'),
                                $cp('header_icon_hover', 'Action Hover Color'),
                                $cp('header_icon_bg_hover', 'Action Hover Background'),
                                $cp('header_badge_bg', 'Cart/Wishlist Badge BG'),
                                $cp('header_badge_text', 'Badge Text Color'),
                            ])
                            ->columns(3),
                    ]),

                Forms\Components\Section::make('Cart Drawer & Free Gift Bar')
                    ->description('Configure the free gift unlocked progress bar in the side cart drawer')
                    ->schema([
                        Forms\Components\Toggle::make('enable_free_gift')
                            ->label('Enable Free Gift Progress Bar')
                            ->helperText('Show or hide the free gift promotion bar in the slide-out cart')
                            ->default(true),

                        Forms\Components\TextInput::make('free_gift_min')
                            ->label('Minimum Order for Free Gift (৳)')
                            ->helperText('Cart subtotal required to unlock this gift (e.g. 3000)')
                            ->numeric(),

                        Forms\Components\TextInput::make('free_gift_name')
                            ->label('Gift Item / Offer Name')
                            ->helperText('e.g. "free Lychee Honey sachet" or "free Mustard Oil 100g"'),

                        Forms\Components\TextInput::make('free_gift_success_msg')
                            ->label('Reward Message (When Unlocked)')
                            ->helperText('Message displayed when customer reaches the minimum amount'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Main Menu & Nav Bar')
                    ->description('Menu bar background color, "All Categories" button and nav links')
                    ->schema([
                        Forms\Components\Fieldset::make('Menu Bar Background')
                            ->schema([
                                $cp('menu_bg', 'Menu Bar Background Color')
                                    ->helperText('Change background color of the entire navigation menu bar (e.g. #143A2C)'),
                            ])
                            ->columns(1),

                        Forms\Components\Fieldset::make('All Categories Button')
                            ->schema([
                                $cp('nav_bg', 'BG'),
                                $cp('nav_bg_hover', 'BG Hover'),
                                $cp('nav_text', 'Text'),
                            ])
                            ->columns(3),

                        Forms\Components\Fieldset::make('Nav Links')
                            ->schema([
                                $cp('nav_link_text', 'Text'),
                                $cp('nav_link_hover_text', 'Hover Text'),
                                $cp('nav_link_hover_bg', 'Hover BG'),
                            ])
                            ->columns(3),
                    ]),

                Forms\Components\Section::make('Primary Button')
                    ->description('Login, Checkout, Save, Place Order etc.')
                    ->schema([
                        $cp('btn_bg', 'BG'),
                        $cp('btn_bg_hover', 'BG Hover'),
                        $cp('btn_text', 'Text'),
                        $cp('btn_text_hover', 'Text Hover'),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Add to Cart Button')
                    ->schema([
                        $cp('addcart_bg', 'BG'),
                        $cp('addcart_bg_hover', 'BG Hover'),
                        $cp('addcart_text', 'Text'),
                        $cp('addcart_text_hover', 'Text Hover'),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Buy Now Button')
                    ->schema([
                        $cp('buynow_bg', 'BG'),
                        $cp('buynow_bg_hover', 'BG Hover'),
                        $cp('buynow_text', 'Text'),
                        $cp('buynow_text_hover', 'Text Hover'),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Slider / CTA Button')
                    ->description('Banner slider & hero section buttons')
                    ->schema([
                        $cp('slider_bg', 'BG'),
                        $cp('slider_bg_hover', 'BG Hover'),
                        $cp('slider_text', 'Text'),
                        $cp('slider_text_hover', 'Text Hover'),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Text & Links')
                    ->schema([
                        $cp('text_color', 'Body Text'),
                        $cp('text_muted', 'Muted Text'),
                        $cp('link_color', 'Link Color'),
                        $cp('link_hover', 'Link Hover'),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Footer')
                    ->schema([
                        Forms\Components\Fieldset::make('Colors')
                            ->schema([
                                $cp('footer_bg', 'Background'),
                                $cp('footer_text_color', 'Text Color'),
                                $cp('footer_link_hover', 'Link Hover'),
                            ])
                            ->columns(3),
                        Forms\Components\TextInput::make('footer_text')->maxLength(255)->columnSpanFull(),
                        Forms\Components\Textarea::make('footer_about')->label('Footer About Text')->rows(2)->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Typography')
                    ->schema([
                        Forms\Components\Select::make('font_heading')->label('Heading Font')->options([
                            'Bricolage Grotesque' => 'Bricolage Grotesque', 'Inter' => 'Inter', 'Poppins' => 'Poppins',
                            'Playfair Display' => 'Playfair Display', 'Montserrat' => 'Montserrat', 'Roboto' => 'Roboto',
                            'Nunito' => 'Nunito', 'Raleway' => 'Raleway', 'Oswald' => 'Oswald',
                            'Merriweather' => 'Merriweather', 'Lora' => 'Lora',
                            'Noto Sans Bengali' => 'Noto Sans Bengali', 'Hind Siliguri' => 'Hind Siliguri',
                        ]),
                        Forms\Components\Select::make('font_body')->label('Body Font')->options([
                            'Hanken Grotesk' => 'Hanken Grotesk', 'Inter' => 'Inter', 'Open Sans' => 'Open Sans',
                            'Lato' => 'Lato', 'Nunito' => 'Nunito', 'Roboto' => 'Roboto', 'Poppins' => 'Poppins',
                            'Raleway' => 'Raleway', 'Noto Sans Bengali' => 'Noto Sans Bengali', 'Hind Siliguri' => 'Hind Siliguri',
                        ]),
                        Forms\Components\Select::make('base_font_size')->label('Base Font Size')->options([
                            '13' => '13px (Small)', '14' => '14px (Compact)', '15' => '15px (Default)',
                            '16' => '16px (Medium)', '17' => '17px (Large)', '18' => '18px (Extra Large)',
                        ]),
                        Forms\Components\Select::make('heading_scale')->label('Heading Size Scale')->options([
                            '0.85' => '85% (Smaller)', '0.9' => '90% (Compact)', '1' => '100% (Default)',
                            '1.1' => '110% (Larger)', '1.2' => '120% (Extra Large)', '1.3' => '130% (Jumbo)',
                        ]),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Social Links')
                    ->schema([
                        Forms\Components\TextInput::make('social_facebook')->label('Facebook URL')->url()->maxLength(255),
                        Forms\Components\TextInput::make('social_instagram')->label('Instagram URL')->url()->maxLength(255),
                        Forms\Components\TextInput::make('social_youtube')->label('YouTube URL')->url()->maxLength(255),
                        Forms\Components\TextInput::make('social_whatsapp')->label('WhatsApp Number')->placeholder('+8801XXXXXXXXX')->maxLength(20),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('contact_phone')->maxLength(30),
                        Forms\Components\TextInput::make('contact_email')->email()->maxLength(255),
                        Forms\Components\Textarea::make('contact_address')->rows(2)->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Mobile Header Colors')
                    ->description('Search bar, cart button & tab navigation colors on mobile')
                    ->icon('heroicon-o-device-phone-mobile')
                    ->schema([
                        $cp('mob_search_bg', 'Search Bar BG'),
                        $cp('mob_search_text', 'Search Bar Text'),
                        $cp('mob_cart_bg', 'Cart Button BG'),
                        $cp('mob_cart_text', 'Cart Button Icon'),
                        $cp('mob_tab_text', 'Tab Text'),
                        $cp('mob_tab_active', 'Tab Active'),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Forms\Components\Section::make('Mobile Bottom Nav')
                    ->description('Footer navigation bar colors & tab visibility')
                    ->icon('heroicon-o-device-phone-mobile')
                    ->schema([
                        Forms\Components\Fieldset::make('Colors')
                            ->schema([
                                $cp('mobnav_bg', 'Background'),
                                $cp('mobnav_text', 'Icon/Text'),
                                $cp('mobnav_active', 'Active Color'),
                            ])
                            ->columns(3),
                        Forms\Components\Fieldset::make('Visible Tabs')
                            ->schema([
                                Forms\Components\Toggle::make('mobnav_home')->label('Home')->default(true),
                                Forms\Components\Toggle::make('mobnav_categories')->label('Categories')->default(false),
                                Forms\Components\Toggle::make('mobnav_shop')->label('Shop')->default(true),
                                Forms\Components\Toggle::make('mobnav_cart')->label('Cart')->default(true),
                                Forms\Components\Toggle::make('mobnav_wishlist')->label('Wishlist')->default(true),
                                Forms\Components\Toggle::make('mobnav_account')->label('Account / Sign in')->default(true),
                            ])
                            ->columns(6),
                    ])
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach ($this->themeKeys() as $formKey => $opts) {
            $dbKey = $opts['key'] ?? $formKey;
            $val = $state[$formKey] ?? null;
            if (is_bool($val)) {
                Setting::set($dbKey, $val);
            } else {
                Setting::set($dbKey, $val ?? '');
            }
        }

        Notification::make()
            ->title('Theme settings saved')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save Theme Settings')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }
}
