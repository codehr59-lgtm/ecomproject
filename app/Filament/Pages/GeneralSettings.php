<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class GeneralSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationLabel = 'General Settings';

    protected static ?int $navigationSort = 36;

    protected static string $view = 'filament.pages.general-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'free_shipping_min'  => Setting::get('free_shipping_min', 1500),
            'default_delivery'   => Setting::get('default_delivery', 60),
            'inside_dhaka'       => Setting::get('delivery_inside_dhaka', 60),
            'outside_dhaka'      => Setting::get('delivery_outside_dhaka', 120),
            'enable_free_gift'   => (bool) Setting::get('enable_free_gift', true),
            'free_gift_min'      => (int) Setting::get('free_gift_min', 3000),
            'free_gift_name'     => Setting::get('free_gift_name', 'free Lychee Honey sachet'),
            'free_gift_success_msg' => Setting::get('free_gift_success_msg', "🎉 You've unlocked a free gift! It'll be added at checkout."),
            'low_stock_threshold'=> Setting::get('low_stock_threshold', 5),
            'order_prefix'       => Setting::get('order_prefix', 'SHV'),
            'currency_symbol'    => Setting::get('currency_symbol', '৳'),
            'items_per_page'     => Setting::get('items_per_page', 20),
            'allow_guest_checkout' => Setting::get('allow_guest_checkout', true),
            'maintenance_mode'   => Setting::get('maintenance_mode', false),
            'maintenance_msg'    => Setting::get('maintenance_msg', 'We are currently performing maintenance. Please check back soon.'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Shipping & Delivery')
                    ->schema([
                        Forms\Components\TextInput::make('free_shipping_min')
                            ->label('Free Shipping Minimum (৳)')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('default_delivery')
                            ->label('Default Delivery Fee (৳)')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('inside_dhaka')
                            ->label('Inside Dhaka (৳)')
                            ->numeric(),

                        Forms\Components\TextInput::make('outside_dhaka')
                            ->label('Outside Dhaka (৳)')
                            ->numeric(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Cart Free Gift Promotion')
                    ->description('Configure the free gift unlocked progress bar in the side cart drawer')
                    ->schema([
                        Forms\Components\Toggle::make('enable_free_gift')
                            ->label('Enable Free Gift Progress Bar')
                            ->helperText('Show or hide the free gift promotion bar in the slide-out cart')
                            ->default(true),

                        Forms\Components\TextInput::make('free_gift_min')
                            ->label('Minimum Order for Free Gift (৳)')
                            ->helperText('Cart subtotal required to unlock this gift (e.g. 3000)')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('free_gift_name')
                            ->label('Gift Item / Offer Name')
                            ->helperText('e.g. "free Lychee Honey sachet" or "free Mustard Oil 100g"')
                            ->required(),

                        Forms\Components\TextInput::make('free_gift_success_msg')
                            ->label('Reward Message (When Unlocked)')
                            ->helperText('Message displayed when customer reaches the minimum amount')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Store')
                    ->schema([
                        Forms\Components\TextInput::make('order_prefix')
                            ->maxLength(10)
                            ->placeholder('SHV'),

                        Forms\Components\TextInput::make('currency_symbol')
                            ->maxLength(5)
                            ->placeholder('৳'),

                        Forms\Components\TextInput::make('low_stock_threshold')
                            ->label('Low Stock Alert Threshold')
                            ->numeric()
                            ->helperText('Products with stock below this will show as low stock'),

                        Forms\Components\TextInput::make('items_per_page')
                            ->label('Products Per Page')
                            ->numeric(),

                        Forms\Components\Toggle::make('allow_guest_checkout')
                            ->label('Allow Guest Checkout'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Maintenance Mode')
                    ->schema([
                        Forms\Components\Toggle::make('maintenance_mode')
                            ->label('Enable Maintenance Mode')
                            ->helperText('Shows maintenance page to visitors (admin panel stays accessible)'),

                        Forms\Components\Textarea::make('maintenance_msg')
                            ->label('Maintenance Message')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $keys = [
            'free_shipping_min', 'default_delivery', 'low_stock_threshold',
            'enable_free_gift', 'free_gift_min', 'free_gift_name', 'free_gift_success_msg',
            'order_prefix', 'currency_symbol', 'items_per_page',
            'allow_guest_checkout', 'maintenance_mode', 'maintenance_msg',
        ];

        foreach ($keys as $key) {
            if (array_key_exists($key, $state)) {
                Setting::set($key, $state[$key]);
            }
        }

        Setting::set('delivery_inside_dhaka', $state['inside_dhaka'] ?? null);
        Setting::set('delivery_outside_dhaka', $state['outside_dhaka'] ?? null);

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }
}
