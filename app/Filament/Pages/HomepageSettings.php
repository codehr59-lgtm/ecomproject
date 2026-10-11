<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class HomepageSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Homepage Builder';

    protected static ?int $navigationSort = 19;

    protected static string $view = 'filament.pages.homepage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $defaultRails = [
            [
                'is_active'   => true,
                'category_id' => Category::where('slug', 'mango')->value('id') ?? 7,
                'title'       => 'Mango',
                'limit'       => 5,
                'sort_by'     => 'sort_order',
            ],
            [
                'is_active'   => true,
                'category_id' => Category::where('slug', 'honey')->value('id') ?? 1,
                'title'       => 'All Natural Honey',
                'limit'       => 5,
                'sort_by'     => 'sort_order',
            ],
            [
                'is_active'   => true,
                'category_id' => Category::where('slug', 'dates')->value('id') ?? 2,
                'title'       => 'Premium Dates',
                'limit'       => 5,
                'sort_by'     => 'sort_order',
            ],
            [
                'is_active'   => true,
                'category_id' => Category::where('slug', 'oil-ghee')->value('id') ?? 3,
                'title'       => 'Cooking Essentials',
                'limit'       => 5,
                'sort_by'     => 'sort_order',
            ],
        ];

        $savedRails = Setting::get('homepage_category_rails');
        if (! is_array($savedRails) || empty($savedRails)) {
            $savedRails = $defaultRails;
        }

        $this->form->fill([
            'hero_slider_height_pc'      => (int) Setting::get('hero_slider_height_pc', 400),
            'hero_banner_height_pc'      => (int) Setting::get('hero_banner_height_pc', 400),
            'hero_split_ratio_pc'        => (string) Setting::get('hero_split_ratio_pc', '1.85fr 1fr'),
            'hero_slider_fit'            => (string) Setting::get('hero_slider_fit', 'cover'),
            'hero_banner_fit'            => (string) Setting::get('hero_banner_fit', 'cover'),
            'hero_slider_height_mobile'  => (int) Setting::get('hero_slider_height_mobile', 220),
            'hero_banner_height_mobile'  => (int) Setting::get('hero_banner_height_mobile', 180),
            'hero_banner_width_mobile'   => (string) Setting::get('hero_banner_width_mobile', '100%'),
            'hero_banner_show_mobile'    => (bool) Setting::get('hero_banner_show_mobile', true),
            'slider_autoplay'        => Setting::get('homepage_slider_autoplay', true),
            'slider_speed'           => Setting::get('homepage_slider_speed', 4),
            'slider_arrows'          => Setting::get('homepage_slider_arrows', true),
            'slider_dots'            => Setting::get('homepage_slider_dots', true),
            'slider_per_view'        => Setting::get('homepage_slider_per_view', '4'),
            'hero_slider'            => Setting::get('homepage_hero_slider', true),
            'featured_categories'    => Setting::get('homepage_featured_categories', true),
            'featured_category_ids'  => Setting::get('homepage_featured_category_ids', []),
            'category_rails'         => $savedRails,
            'auto_show_category_rails' => (bool) Setting::get('homepage_auto_show_category_rails', true),
            'top_selling'            => Setting::get('homepage_top_selling', true),
            'top_selling_title'      => Setting::get('homepage_top_selling_title', 'Top Selling Products'),
            'top_selling_limit'      => Setting::get('homepage_top_selling_limit', 4),
            'combos'                 => Setting::get('homepage_combos', true),
            'certified'              => Setting::get('homepage_certified', true),
            'certified_title'        => Setting::get('homepage_certified_title', 'Organic Certified'),
            'just_for_you'           => Setting::get('homepage_just_for_you', true),
            'just_for_you_title'     => Setting::get('homepage_just_for_you_title', 'Just For You'),
            'just_for_you_limit'     => Setting::get('homepage_just_for_you_limit', 10),
            'testimonials'           => Setting::get('homepage_testimonials', true),
            'brands'                 => Setting::get('homepage_brands', true),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Hero Slider & Banner Sizing (হিরো স্লাইডার ও ব্যানার সাইজ নিয়ন্ত্রণ — PC ও Phone)')
                    ->description('ডেস্কটপ (PC) এবং মোবাইল (Phone) উভয়ের জন্য হিরো স্লাইডার এবং ব্যানারের উচ্চতা (Height), প্রস্থ (Width), এবং ডিসপ্লে স্টাইল প্রয়োজনমতো বাড়ান বা কমান।')
                    ->schema([
                        Forms\Components\Fieldset::make('Desktop / PC Sizing (কম্পিউটার ও ডেস্কটপ সাইজ)')
                            ->schema([
                                Forms\Components\TextInput::make('hero_slider_height_pc')
                                    ->label('Slider Height on PC (ডেস্কটপ স্লাইডার উচ্চতা)')
                                    ->numeric()
                                    ->default(400)
                                    ->minValue(150)
                                    ->maxValue(900)
                                    ->suffix('px')
                                    ->helperText('ডিফল্ট: 400px (প্রয়োজনে 300px থেকে 600px এর মধ্যে সেট করুন)'),

                                Forms\Components\TextInput::make('hero_banner_height_pc')
                                    ->label('Banner Height on PC (ডেস্কটপ ব্যানার উচ্চতা)')
                                    ->numeric()
                                    ->default(400)
                                    ->minValue(150)
                                    ->maxValue(900)
                                    ->suffix('px')
                                    ->helperText('ডিফল্ট: 400px (স্লাইডারের সমান সাইজ পেতে একই মান রাখুন)'),

                                Forms\Components\Select::make('hero_split_ratio_pc')
                                    ->label('Slider vs Banner Width (প্রস্থ অনুপাত — PC)')
                                    ->options([
                                        '1.85fr 1fr' => 'Slider 65% / Banner 35% (স্ট্যান্ডার্ড ডিফল্ট)',
                                        '2fr 1fr'    => 'Slider 67% / Banner 33% (স্লাইডার একটু চওড়া)',
                                        '2.5fr 1fr'  => 'Slider 71% / Banner 29% (স্লাইডার বেশি চওড়া)',
                                        '1.5fr 1fr'  => 'Slider 60% / Banner 40% (ব্যানার একটু বড়)',
                                        '1fr 1fr'    => 'Slider 50% / Banner 50% (স্লাইডার ও ব্যানার সমান)',
                                        '3fr 1fr'    => 'Slider 75% / Banner 25% (খুব চওড়া স্লাইডার)',
                                    ])
                                    ->default('1.85fr 1fr')
                                    ->helperText('ডেস্কটপে স্লাইডার এবং পাশের ব্যানারের প্রস্থ নির্ধারণ করুন'),

                                Forms\Components\Select::make('hero_slider_fit')
                                    ->label('Slider Image Fit (স্লাইডার ছবি প্রদর্শনের ধরন)')
                                    ->options([
                                        'cover'   => 'Cover (পুরো বক্স ভরাট করবে, কোনো ফাঁকা থাকবে না)',
                                        'contain' => 'Contain (না কেটে মূল অনুপাত অনুযায়ী পুরো ছবি দেখাবে)',
                                    ])
                                    ->default('cover'),

                                Forms\Components\Select::make('hero_banner_fit')
                                    ->label('Banner Image Fit (ব্যানার ছবি প্রদর্শনের ধরন)')
                                    ->options([
                                        'cover'   => 'Cover (পুরো বক্স ভরাট করবে, কোনো ফাঁকা থাকবে না)',
                                        'contain' => 'Contain (না কেটে মূল অনুপাত অনুযায়ী পুরো ছবি দেখাবে)',
                                    ])
                                    ->default('cover'),
                            ])
                            ->columns(2),

                        Forms\Components\Fieldset::make('Mobile / Phone Sizing (মোবাইল ও ফোন সাইজ)')
                            ->schema([
                                Forms\Components\TextInput::make('hero_slider_height_mobile')
                                    ->label('Slider Height on Phone (মোবাইল স্লাইডার উচ্চতা)')
                                    ->numeric()
                                    ->default(220)
                                    ->minValue(100)
                                    ->maxValue(600)
                                    ->suffix('px')
                                    ->helperText('ডিফল্ট: 220px (প্রয়োজনে 160px থেকে 320px এর মধ্যে সেট করুন)'),

                                Forms\Components\TextInput::make('hero_banner_height_mobile')
                                    ->label('Banner Height on Phone (মোবাইল ব্যানার উচ্চতা)')
                                    ->numeric()
                                    ->default(180)
                                    ->minValue(80)
                                    ->maxValue(600)
                                    ->suffix('px')
                                    ->helperText('ডিফল্ট: 180px (প্রয়োজনে 130px থেকে 260px এর মধ্যে সেট করুন)'),

                                Forms\Components\Select::make('hero_banner_width_mobile')
                                    ->label('Banner Width on Phone (মোবাইল ব্যানার প্রস্থ)')
                                    ->options([
                                        '100%' => '100% (Full Width — পুরো স্ক্রিন জুড়ে)',
                                        '95%'  => '95% (স্ক্রিনের দুই পাশে ৫% ফাঁকা মার্জিন)',
                                        '90%'  => '90% (স্ক্রিনের দুই পাশে ১০% ফাঁকা মার্জিন)',
                                        '85%'  => '85%',
                                    ])
                                    ->default('100%')
                                    ->helperText('মোবাইলে ব্যানারের পাশাপাশি বিস্তার'),

                                Forms\Components\Toggle::make('hero_banner_show_mobile')
                                    ->label('Show Banner on Mobile (মোবাইলে ব্যানার দেখাও)')
                                    ->default(true)
                                    ->helperText('বন্ধ করলে মোবাইলে শুধু স্লাইডারটি দেখাবে, ব্যানারটি লুকানো থাকবে'),
                            ])
                            ->columns(2),
                    ]),

                Forms\Components\Section::make('Product Sliders Controls (প্রোডাক্ট স্লাইডার কন্ট্রোল)')
                    ->description('Control auto-sliding, speed, navigation arrows, pagination dots, and cards per view for all category product sliders on the homepage.')
                    ->schema([
                        Forms\Components\Toggle::make('slider_autoplay')
                            ->label('Enable Autoplay (স্বয়ংক্রিয় স্লাইড)')
                            ->helperText('Automatically slide products continuously in a smooth loop')
                            ->default(true),

                        Forms\Components\Select::make('slider_speed')
                            ->label('Autoplay Interval (স্লাইড বিরতি)')
                            ->options([
                                2 => '2 Seconds (Fast)',
                                3 => '3 Seconds (Normal)',
                                4 => '4 Seconds (Recommended)',
                                5 => '5 Seconds (Relaxed)',
                                6 => '6 Seconds (Slow)',
                                8 => '8 Seconds (Very Slow)',
                            ])
                            ->default(4)
                            ->helperText('How long each slide stays before moving to the next'),

                        Forms\Components\Toggle::make('slider_arrows')
                            ->label('Show Next / Previous Arrows (তীর চিহ্ন)')
                            ->helperText('Display left & right circular navigation buttons')
                            ->default(true),

                        Forms\Components\Toggle::make('slider_dots')
                            ->label('Show Pagination Dots (নিচের ডটসমূহ)')
                            ->helperText('Display interactive dots below the products to jump to slides')
                            ->default(true),

                        Forms\Components\Select::make('slider_per_view')
                            ->label('Desktop Cards Visible (ডেস্কটপে একসাথে দৃশ্যমান প্রোডাক্ট)')
                            ->options([
                                '4' => '4 Cards (Recommended — larger cards with smooth sliding)',
                                '5' => '5 Cards (Compact)',
                            ])
                            ->default('4')
                            ->helperText('Number of product cards shown at once on large desktop screens'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Category Rails (Homepage Products by Category)')
                    ->description('Choose which categories will appear as individual product sections on the home page. You can customize section titles, choose how many products to display, change their order by dragging, or turn them on/off.')
                    ->schema([
                        Forms\Components\Repeater::make('category_rails')
                            ->label('Category Product Sections')
                            ->schema([
                                Forms\Components\Grid::make(12)
                                    ->schema([
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Active')
                                            ->default(true)
                                            ->columnSpan(2),

                                        Forms\Components\Select::make('category_id')
                                            ->label('Category')
                                            ->options(fn () => Category::withCount('products')
                                                ->orderBy('name')
                                                ->get()
                                                ->mapWithKeys(fn ($c) => [
                                                    $c->id => "{$c->name} ({$c->products_count} " . ($c->products_count === 1 ? 'product' : 'products') . ")"
                                                ]))
                                            ->required()
                                            ->searchable()
                                            ->columnSpan(4),

                                        Forms\Components\TextInput::make('title')
                                            ->label('Section Title')
                                            ->placeholder('e.g. Fresh Mangoes (leave blank for category name)')
                                            ->columnSpan(4),

                                        Forms\Components\TextInput::make('limit')
                                            ->label('Product Count')
                                            ->numeric()
                                            ->default(10)
                                            ->minValue(1)
                                            ->maxValue(30)
                                            ->helperText('Max products in slider (e.g. 10)')
                                            ->columnSpan(2),

                                        Forms\Components\Select::make('sort_by')
                                            ->label('Product Sorting')
                                            ->options([
                                                'sort_order' => 'Default Sort Order',
                                                'latest'     => 'Latest Added',
                                                'popular'    => 'Most Popular / Reviewed',
                                                'price_asc'  => 'Price: Low to High',
                                                'price_desc' => 'Price: High to Low',
                                            ])
                                            ->default('sort_order')
                                            ->columnSpan(6),
                                    ]),
                            ])
                            ->itemLabel(function (array $state): ?string {
                                if (empty($state['category_id'])) {
                                    return 'New Category Rail';
                                }
                                $cat = Category::withCount('products')->find($state['category_id']);
                                if (! $cat) {
                                    $title = ! empty($state['title']) ? $state['title'] : 'Deleted Category';
                                    return $title . ' (⚠ Deleted Category — please reselect or delete)';
                                }
                                $title = ! empty($state['title']) ? $state['title'] : $cat->name;
                                $count = $cat->products_count ?? 0;
                                return $title . ' (' . $cat->name . ' — ' . $count . ' ' . ($count === 1 ? 'product' : 'products') . ')';
                            })
                            ->collapsible()
                            ->cloneable()
                            ->reorderable()
                            ->addActionLabel('+ Add New Category Section')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('auto_show_category_rails')
                            ->label('Auto-Display New Categories with Products (নতুন ক্যাটাগরিতে প্রোডাক্ট থাকলে স্বয়ংক্রিয়ভাবে হোমপেজে দেখাও)')
                            ->helperText('When enabled, any active category that has products will automatically appear on the homepage, even before you manually add it to the list above.')
                            ->default(true)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Top Featured Categories Slider')
                    ->description('Settings for the circular/icon category slider near the top of the homepage.')
                    ->schema([
                        Forms\Components\Toggle::make('featured_categories')
                            ->label('Show Top Category Slider')
                            ->default(true),

                        Forms\Components\Select::make('featured_category_ids')
                            ->label('Select Specific Categories to Show')
                            ->helperText('Leave empty to show all active categories automatically.')
                            ->multiple()
                            ->options(Category::pluck('name', 'id'))
                            ->searchable(),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Other Homepage Sections')
                    ->description('Show or hide other home page blocks and customize titles.')
                    ->schema([
                        Forms\Components\Toggle::make('hero_slider')
                            ->label('Hero Slider & Banner')
                            ->helperText('Top carousel banner'),

                        Forms\Components\Toggle::make('top_selling')
                            ->label('Top Selling Products Rail'),

                        Forms\Components\TextInput::make('top_selling_title')
                            ->label('Top Selling Title')
                            ->default('Top Selling Products'),

                        Forms\Components\TextInput::make('top_selling_limit')
                            ->label('Top Selling Item Count')
                            ->numeric()
                            ->default(4),

                        Forms\Components\Toggle::make('combos')
                            ->label('Exclusive Combo Deals Rail'),

                        Forms\Components\Toggle::make('certified')
                            ->label('Organic Certified Rail'),

                        Forms\Components\TextInput::make('certified_title')
                            ->label('Organic Certified Title')
                            ->default('Organic Certified'),

                        Forms\Components\Toggle::make('just_for_you')
                            ->label('Just For You (All Products Grid)'),

                        Forms\Components\TextInput::make('just_for_you_title')
                            ->label('Just For You Title')
                            ->default('Just For You'),

                        Forms\Components\TextInput::make('just_for_you_limit')
                            ->label('Just For You Item Count')
                            ->numeric()
                            ->default(10),

                        Forms\Components\Toggle::make('brands')
                            ->label('Our Brands Section'),

                        Forms\Components\Toggle::make('testimonials')
                            ->label('Customer Testimonials Section'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        Setting::set('hero_slider_height_pc', (int) ($state['hero_slider_height_pc'] ?? 400));
        Setting::set('hero_banner_height_pc', (int) ($state['hero_banner_height_pc'] ?? 400));
        Setting::set('hero_split_ratio_pc', (string) ($state['hero_split_ratio_pc'] ?? '1.85fr 1fr'));
        Setting::set('hero_slider_fit', (string) ($state['hero_slider_fit'] ?? 'cover'));
        Setting::set('hero_banner_fit', (string) ($state['hero_banner_fit'] ?? 'cover'));

        Setting::set('hero_slider_height_mobile', (int) ($state['hero_slider_height_mobile'] ?? 220));
        Setting::set('hero_banner_height_mobile', (int) ($state['hero_banner_height_mobile'] ?? 180));
        Setting::set('hero_banner_width_mobile', (string) ($state['hero_banner_width_mobile'] ?? '100%'));
        Setting::set('hero_banner_show_mobile', (bool) ($state['hero_banner_show_mobile'] ?? true));

        Setting::set('homepage_slider_autoplay', (bool) ($state['slider_autoplay'] ?? true));
        Setting::set('homepage_slider_speed', (int) ($state['slider_speed'] ?? 4));
        Setting::set('homepage_slider_arrows', (bool) ($state['slider_arrows'] ?? true));
        Setting::set('homepage_slider_dots', (bool) ($state['slider_dots'] ?? true));
        Setting::set('homepage_slider_per_view', (string) ($state['slider_per_view'] ?? '4'));

        Setting::set('homepage_category_rails', $state['category_rails'] ?? []);
        Setting::set('homepage_featured_categories', $state['featured_categories'] ?? true);
        Setting::set('homepage_featured_category_ids', $state['featured_category_ids'] ?? []);

        Setting::set('homepage_hero_slider', $state['hero_slider'] ?? true);
        Setting::set('homepage_top_selling', $state['top_selling'] ?? true);
        Setting::set('homepage_top_selling_title', $state['top_selling_title'] ?? 'Top Selling Products');
        Setting::set('homepage_top_selling_limit', (int) ($state['top_selling_limit'] ?? 4));

        Setting::set('homepage_combos', $state['combos'] ?? true);
        Setting::set('homepage_certified', $state['certified'] ?? true);
        Setting::set('homepage_certified_title', $state['certified_title'] ?? 'Organic Certified');

        Setting::set('homepage_just_for_you', $state['just_for_you'] ?? true);
        Setting::set('homepage_just_for_you_title', $state['just_for_you_title'] ?? 'Just For You');
        Setting::set('homepage_just_for_you_limit', (int) ($state['just_for_you_limit'] ?? 10));

        Setting::set('homepage_auto_show_category_rails', (bool) ($state['auto_show_category_rails'] ?? true));

        Setting::set('homepage_brands', $state['brands'] ?? true);
        Setting::set('homepage_testimonials', $state['testimonials'] ?? true);

        // Immediately flush all catalog & homepage caches so changes appear in real-time
        \App\Support\Catalog::flushCache();

        Notification::make()
            ->title('Homepage settings saved successfully!')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save Homepage Settings')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }
}

