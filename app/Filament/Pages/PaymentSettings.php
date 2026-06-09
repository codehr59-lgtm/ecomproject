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
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Payment Settings';
    protected static ?string $title           = 'Payment Settings';
    protected static string  $view            = 'filament.pages.payment-settings';
    protected static ?int    $navigationSort  = 90;

    /** Form state */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            // COD
            'cod_enabled'              => (bool) Setting::get('cod_enabled', true),

            // bKash
            'bkash_enabled'            => (bool) Setting::get('bkash_enabled', false),
            'bkash_app_key'            => Setting::get('bkash_app_key', ''),
            // bkash_app_secret: intentionally blank — show hint
            'bkash_app_secret'         => '',
            'bkash_username'           => Setting::get('bkash_username', ''),
            // bkash_password: intentionally blank
            'bkash_password'           => '',
            'bkash_sandbox'            => (bool) Setting::get('bkash_sandbox', true),

            // SSLCommerz
            'sslcommerz_enabled'       => (bool) Setting::get('sslcommerz_enabled', false),
            'sslcommerz_store_id'      => Setting::get('sslcommerz_store_id', ''),
            // sslcommerz_store_password: intentionally blank
            'sslcommerz_store_password'=> '',
            'sslcommerz_sandbox'       => (bool) Setting::get('sslcommerz_sandbox', true),
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
                    ]),

                Section::make('bKash')
                    ->description('Accept payments via bKash Tokenized Checkout. Credentials from developer.bka.sh.')
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
                    ]),

                Section::make('SSLCommerz')
                    ->description('Accept cards and mobile banking via SSLCommerz. Credentials from merchant.sslcommerz.com.')
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
                    ]),

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

        // Plain non-secret settings
        Setting::set('cod_enabled',         (bool) ($state['cod_enabled'] ?? true));

        Setting::set('bkash_enabled',       (bool) ($state['bkash_enabled'] ?? false));
        Setting::set('bkash_app_key',       (string) ($state['bkash_app_key'] ?? ''));
        Setting::set('bkash_username',      (string) ($state['bkash_username'] ?? ''));
        Setting::set('bkash_sandbox',       (bool) ($state['bkash_sandbox'] ?? true));

        Setting::set('sslcommerz_enabled',  (bool) ($state['sslcommerz_enabled'] ?? false));
        Setting::set('sslcommerz_store_id', (string) ($state['sslcommerz_store_id'] ?? ''));
        Setting::set('sslcommerz_sandbox',  (bool) ($state['sslcommerz_sandbox'] ?? true));

        // Secret fields — only write if non-empty
        if (! empty($state['bkash_app_secret'])) {
            Setting::set('bkash_app_secret', $state['bkash_app_secret']);
        }
        if (! empty($state['bkash_password'])) {
            Setting::set('bkash_password', $state['bkash_password']);
        }
        if (! empty($state['sslcommerz_store_password'])) {
            Setting::set('sslcommerz_store_password', $state['sslcommerz_store_password']);
        }

        Notification::make()
            ->title('Payment settings saved successfully.')
            ->success()
            ->send();
    }
}
