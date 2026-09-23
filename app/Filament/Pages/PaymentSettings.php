<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PaymentSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Payment Settings';
    protected static ?string $title           = 'Payment & Courier Settings';
    protected static string  $view            = 'filament.pages.payment-settings';
    protected static ?int    $navigationSort  = 12;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            // COD
            'cod_enabled'              => (bool) Setting::get('cod_enabled', true),
            'delivery_zone_1_label'    => Setting::get('delivery_zone_1_label', 'Inside Dhaka'),
            'delivery_zone_1_charge'   => Setting::get('delivery_inside_dhaka', '60'),
            'delivery_zone_2_label'    => Setting::get('delivery_zone_2_label', 'Outside Dhaka'),
            'delivery_zone_2_charge'   => Setting::get('delivery_outside_dhaka', '120'),
            'free_shipping_min'        => Setting::get('free_shipping_min', '1500'),

            // bKash
            'bkash_enabled'            => (bool) Setting::get('bkash_enabled', false),
            'bkash_app_key'            => Setting::get('bkash_app_key', ''),
            'bkash_app_secret'         => '',
            'bkash_username'           => Setting::get('bkash_username', ''),
            'bkash_password'           => '',
            'bkash_sandbox'            => (bool) Setting::get('bkash_sandbox', true),

            // Nagad
            'nagad_enabled'            => (bool) Setting::get('nagad_enabled', false),
            'nagad_merchant_id'        => Setting::get('nagad_merchant_id', ''),
            'nagad_merchant_key'       => '',
            'nagad_sandbox'            => (bool) Setting::get('nagad_sandbox', true),

            // Rocket
            'rocket_enabled'           => (bool) Setting::get('rocket_enabled', false),
            'rocket_merchant_id'       => Setting::get('rocket_merchant_id', ''),
            'rocket_merchant_password' => '',
            'rocket_sandbox'           => (bool) Setting::get('rocket_sandbox', true),

            // SSLCommerz
            'sslcommerz_enabled'       => (bool) Setting::get('sslcommerz_enabled', false),
            'sslcommerz_store_id'      => Setting::get('sslcommerz_store_id', ''),
            'sslcommerz_store_password'=> '',
            'sslcommerz_sandbox'       => (bool) Setting::get('sslcommerz_sandbox', true),

            // Pathao
            'pathao_client_id'         => Setting::get('pathao_client_id', ''),
            'pathao_client_secret'     => '',
            'pathao_username'          => Setting::get('pathao_username', ''),
            'pathao_password'          => '',
            'pathao_sandbox'           => (bool) Setting::get('pathao_sandbox', true),

            // Steadfast
            'steadfast_api_key'        => Setting::get('steadfast_api_key', ''),
            'steadfast_api_secret'     => '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Cash on Delivery')
                    ->description('Allow customers to pay when the order arrives.')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Toggle::make('cod_enabled')
                            ->label('Enable Cash on Delivery')
                            ->default(true),

                        TextInput::make('delivery_zone_1_label')
                            ->label('Zone 1 Label')
                            ->placeholder('Inside Dhaka')
                            ->helperText('Name for first delivery zone'),

                        TextInput::make('delivery_zone_1_charge')
                            ->label('Zone 1 Charge')
                            ->numeric()
                            ->prefix('৳')
                            ->placeholder('60'),

                        TextInput::make('delivery_zone_2_label')
                            ->label('Zone 2 Label')
                            ->placeholder('Outside Dhaka')
                            ->helperText('Name for second delivery zone'),

                        TextInput::make('delivery_zone_2_charge')
                            ->label('Zone 2 Charge')
                            ->numeric()
                            ->prefix('৳')
                            ->placeholder('120'),

                        TextInput::make('free_shipping_min')
                            ->label('Free Shipping Minimum')
                            ->numeric()
                            ->prefix('৳')
                            ->placeholder('1500')
                            ->helperText('0 = no free shipping')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('bKash')
                    ->description('Accept payments via bKash Tokenized Checkout.')
                    ->icon('heroicon-o-device-phone-mobile')
                    ->schema([
                        Toggle::make('bkash_enabled')
                            ->label('Enable bKash'),

                        TextInput::make('bkash_app_key')
                            ->label('App Key')
                            ->placeholder('bKash App Key')
                            ->maxLength(255),

                        TextInput::make('bkash_app_secret')
                            ->label('App Secret')
                            ->password()
                            ->revealable()
                            ->placeholder(Setting::secret('bkash_app_secret') ? '••••••••  (leave blank to keep current)' : 'bKash App Secret')
                            ->helperText('Leave blank to keep the existing secret.')
                            ->maxLength(255),

                        TextInput::make('bkash_username')
                            ->label('Username')
                            ->placeholder('bKash Username')
                            ->maxLength(255),

                        TextInput::make('bkash_password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->placeholder(Setting::secret('bkash_password') ? '••••••••  (leave blank to keep current)' : 'bKash Password')
                            ->helperText('Leave blank to keep the existing password.')
                            ->maxLength(255),

                        Toggle::make('bkash_sandbox')
                            ->label('Sandbox mode (test)')
                            ->default(true),
                    ])
                    ->collapsible(),

                Section::make('Nagad')
                    ->description('Accept payments via Nagad.')
                    ->icon('heroicon-o-device-phone-mobile')
                    ->schema([
                        Toggle::make('nagad_enabled')
                            ->label('Enable Nagad'),

                        TextInput::make('nagad_merchant_id')
                            ->label('Merchant ID')
                            ->placeholder('Nagad Merchant ID')
                            ->maxLength(255),

                        TextInput::make('nagad_merchant_key')
                            ->label('Merchant Key')
                            ->password()
                            ->revealable()
                            ->placeholder(Setting::secret('nagad_merchant_key') ? '••••••••  (leave blank to keep current)' : 'Nagad Merchant Key')
                            ->helperText('Leave blank to keep the existing key.')
                            ->maxLength(255),

                        Toggle::make('nagad_sandbox')
                            ->label('Sandbox mode (test)')
                            ->default(true),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Rocket (DBBL)')
                    ->description('Accept payments via Dutch-Bangla Rocket.')
                    ->icon('heroicon-o-device-phone-mobile')
                    ->schema([
                        Toggle::make('rocket_enabled')
                            ->label('Enable Rocket'),

                        TextInput::make('rocket_merchant_id')
                            ->label('Merchant ID')
                            ->placeholder('Rocket Merchant ID')
                            ->maxLength(255),

                        TextInput::make('rocket_merchant_password')
                            ->label('Merchant Password')
                            ->password()
                            ->revealable()
                            ->placeholder(Setting::secret('rocket_merchant_password') ? '••••••••  (leave blank to keep current)' : 'Rocket Merchant Password')
                            ->helperText('Leave blank to keep the existing password.')
                            ->maxLength(255),

                        Toggle::make('rocket_sandbox')
                            ->label('Sandbox mode (test)')
                            ->default(true),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('SSLCommerz')
                    ->description('Accept cards and mobile banking via SSLCommerz.')
                    ->icon('heroicon-o-credit-card')
                    ->schema([
                        Toggle::make('sslcommerz_enabled')
                            ->label('Enable SSLCommerz'),

                        TextInput::make('sslcommerz_store_id')
                            ->label('Store ID')
                            ->placeholder('SSLCommerz Store ID')
                            ->maxLength(255),

                        TextInput::make('sslcommerz_store_password')
                            ->label('Store Password')
                            ->password()
                            ->revealable()
                            ->placeholder(Setting::secret('sslcommerz_store_password') ? '••••••••  (leave blank to keep current)' : 'SSLCommerz Store Password')
                            ->helperText('Leave blank to keep the existing password.')
                            ->maxLength(255),

                        Toggle::make('sslcommerz_sandbox')
                            ->label('Sandbox mode (test)')
                            ->default(true),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Pathao Courier')
                    ->description('Integrate with Pathao Courier for order delivery.')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        TextInput::make('pathao_client_id')
                            ->label('Client ID')
                            ->placeholder('Pathao Client ID')
                            ->maxLength(255),

                        TextInput::make('pathao_client_secret')
                            ->label('Client Secret')
                            ->password()
                            ->revealable()
                            ->placeholder(Setting::secret('pathao_client_secret') ? '••••••••  (leave blank to keep current)' : 'Pathao Client Secret')
                            ->helperText('Leave blank to keep the existing secret.')
                            ->maxLength(255),

                        TextInput::make('pathao_username')
                            ->label('Username')
                            ->placeholder('Pathao Username')
                            ->maxLength(255),

                        TextInput::make('pathao_password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->placeholder(Setting::secret('pathao_password') ? '••••••••  (leave blank to keep current)' : 'Pathao Password')
                            ->helperText('Leave blank to keep the existing password.')
                            ->maxLength(255),

                        Toggle::make('pathao_sandbox')
                            ->label('Sandbox mode (test)')
                            ->default(true),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Steadfast Courier')
                    ->description('Integrate with Steadfast Courier for order delivery.')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        TextInput::make('steadfast_api_key')
                            ->label('API Key')
                            ->placeholder('Steadfast API Key')
                            ->maxLength(255),

                        TextInput::make('steadfast_api_secret')
                            ->label('API Secret')
                            ->password()
                            ->revealable()
                            ->placeholder(Setting::secret('steadfast_api_secret') ? '••••••••  (leave blank to keep current)' : 'Steadfast API Secret')
                            ->helperText('Leave blank to keep the existing secret.')
                            ->maxLength(255),
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

        // COD + delivery charges
        Setting::set('cod_enabled',             (bool) ($state['cod_enabled'] ?? true));
        Setting::set('delivery_zone_1_label',   (string) ($state['delivery_zone_1_label'] ?? 'Inside Dhaka'));
        Setting::set('delivery_inside_dhaka',   (string) ($state['delivery_zone_1_charge'] ?? '60'));
        Setting::set('delivery_zone_2_label',   (string) ($state['delivery_zone_2_label'] ?? 'Outside Dhaka'));
        Setting::set('delivery_outside_dhaka',  (string) ($state['delivery_zone_2_charge'] ?? '120'));
        Setting::set('free_shipping_min',       (string) ($state['free_shipping_min'] ?? '1500'));

        Setting::set('bkash_enabled',       (bool) ($state['bkash_enabled'] ?? false));
        Setting::set('bkash_app_key',       (string) ($state['bkash_app_key'] ?? ''));
        Setting::set('bkash_username',      (string) ($state['bkash_username'] ?? ''));
        Setting::set('bkash_sandbox',       (bool) ($state['bkash_sandbox'] ?? true));

        Setting::set('nagad_enabled',       (bool) ($state['nagad_enabled'] ?? false));
        Setting::set('nagad_merchant_id',   (string) ($state['nagad_merchant_id'] ?? ''));
        Setting::set('nagad_sandbox',       (bool) ($state['nagad_sandbox'] ?? true));

        Setting::set('rocket_enabled',      (bool) ($state['rocket_enabled'] ?? false));
        Setting::set('rocket_merchant_id',  (string) ($state['rocket_merchant_id'] ?? ''));
        Setting::set('rocket_sandbox',      (bool) ($state['rocket_sandbox'] ?? true));

        Setting::set('sslcommerz_enabled',  (bool) ($state['sslcommerz_enabled'] ?? false));
        Setting::set('sslcommerz_store_id', (string) ($state['sslcommerz_store_id'] ?? ''));
        Setting::set('sslcommerz_sandbox',  (bool) ($state['sslcommerz_sandbox'] ?? true));

        // Courier plain settings
        Setting::set('pathao_client_id',    (string) ($state['pathao_client_id'] ?? ''));
        Setting::set('pathao_username',     (string) ($state['pathao_username'] ?? ''));
        Setting::set('pathao_sandbox',      (bool) ($state['pathao_sandbox'] ?? true));

        Setting::set('steadfast_api_key',   (string) ($state['steadfast_api_key'] ?? ''));

        // Secret fields — only write if non-empty
        if (! empty($state['bkash_app_secret'])) {
            Setting::set('bkash_app_secret', $state['bkash_app_secret']);
        }
        if (! empty($state['bkash_password'])) {
            Setting::set('bkash_password', $state['bkash_password']);
        }
        if (! empty($state['nagad_merchant_key'])) {
            Setting::set('nagad_merchant_key', $state['nagad_merchant_key']);
        }
        if (! empty($state['rocket_merchant_password'])) {
            Setting::set('rocket_merchant_password', $state['rocket_merchant_password']);
        }
        if (! empty($state['sslcommerz_store_password'])) {
            Setting::set('sslcommerz_store_password', $state['sslcommerz_store_password']);
        }
        if (! empty($state['pathao_client_secret'])) {
            Setting::set('pathao_client_secret', $state['pathao_client_secret']);
        }
        if (! empty($state['pathao_password'])) {
            Setting::set('pathao_password', $state['pathao_password']);
        }
        if (! empty($state['steadfast_api_secret'])) {
            Setting::set('steadfast_api_secret', $state['steadfast_api_secret']);
        }

        Notification::make()
            ->title('Payment & courier settings saved successfully.')
            ->success()
            ->send();
    }
}
