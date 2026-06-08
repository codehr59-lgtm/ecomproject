# Backend B2: Customer Auth via Fortify — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Wire Laravel Fortify (headless) to the existing Shuvo-styled auth views, add address management, and persist the wishlist for authenticated users.

**Architecture:** Fortify handles all auth logic (login/register/password reset) with view callbacks pointing to the existing Shuvo Blade views — no Breeze, no frontend asset changes. Addresses and Wishlists are separate Eloquent models with thin controllers, all using the existing `.co-card`/`.field` design system. Wishlist toggle is hybrid: Alpine updates the UI instantly; for auth users a `fetch` POST persists it via a JSON endpoint.

**Tech Stack:** Laravel 11, Laravel Fortify, SQLite, Alpine.js (existing), Blade components (existing), PHPUnit 11.

---

## Critical Constraints

- **DO NOT** overwrite `resources/css/app.css` or `resources/js/app.js`.
- **DO NOT** install Breeze (`php artisan breeze:install` is forbidden).
- After every `vendor:publish` or `artisan` command, run `git diff --name-only` and restore any clobbered design files with `git checkout -- <file>`.

---

## File Map

### New Files to Create
| File | Responsibility |
|---|---|
| `app/Providers/FortifyServiceProvider.php` | Register Fortify, point it at Shuvo views, add rate limiter |
| `app/Actions/Fortify/CreateNewUser.php` | Validate + create user (name merged from first+last, phone) |
| `app/Actions/Fortify/ResetUserPassword.php` | Reset password action |
| `app/Actions/Fortify/UpdateUserPassword.php` | Update password action |
| `app/Actions/Fortify/UpdateUserProfileInformation.php` | Update name/email/phone |
| `app/Models/Address.php` | Address model, belongsTo User |
| `app/Models/Wishlist.php` | Wishlist model, belongsTo User + Product |
| `app/Http/Controllers/AddressController.php` | CRUD for addresses (auth middleware) |
| `app/Http/Controllers/WishlistController.php` | Toggle wishlist item, return JSON |
| `app/Http/Controllers/AccountController.php` | Show account dashboard with real user data |
| `database/migrations/YYYY_create_addresses_table.php` | addresses table |
| `database/migrations/YYYY_create_wishlists_table.php` | wishlists table |
| `resources/views/pages/forgot-password.blade.php` | Shuvo-styled forgot-password form |
| `resources/views/pages/reset-password.blade.php` | Shuvo-styled reset-password form |
| `tests/Feature/AuthTest.php` | Auth feature tests |

### Existing Files to Modify
| File | Changes |
|---|---|
| `bootstrap/providers.php` | Add `FortifyServiceProvider` |
| `config/fortify.php` | Published; set features, home, guard (after vendor:publish) |
| `routes/web.php` | Remove duplicate GET /login + /register; protect /account + /wishlist; add address + wishlist routes |
| `app/Http/Controllers/PageController.php` | Remove `login()` and `register()` methods; update `account()` to use AccountController; update `wishlist()` for auth users |
| `app/Models/User.php` | Add `hasMany(Address)` and `hasMany(Wishlist)` relationships |
| `resources/views/pages/login.blade.php` | Wire form action to `route('login')`, add `@error` display, wire "Forgot password?" link |
| `resources/views/pages/register.blade.php` | Wire form action to `route('register')`, rename fields (first_name+last_name kept, handled in CreateNewUser), add `@error` display |
| `resources/views/pages/account.blade.php` | Replace dummy data with `auth()->user()` real data; wire profile edit; add addresses section; wire sidebar logout and addresses links; "No orders yet" placeholder |
| `resources/views/pages/wishlist.blade.php` | Render DB wishlist server-side for auth users; keep Alpine x-show for live toggle |
| `resources/views/partials/header.blade.php` | `@auth` show account link + mini logout form; `@guest` show "Sign in" link |
| `resources/views/layouts/app.blade.php` | Add `<meta name="csrf-token">` + `<script>window.AUTH/window.WISHLIST</script>` before `@vite` |
| `resources/views/components/product-card.blade.php` | Update heart `@click` to also call `window.persistWish(id)` when `window.AUTH` |
| `resources/js/app.js` | Add `init()` to `shop` store seeding `wish` from `window.WISHLIST`; add `window.persistWish()` helper |
| `phpunit.xml` | Uncomment sqlite `:memory:` env lines so RefreshDatabase works in AuthTest |
| `database/factories/UserFactory.php` | Add `phone` to definition |
| `tests/Feature/AccountPagesTest.php` | Update: `/account` now redirects to `/login` for guests; `/wishlist` too |

---

## Task 1 — Install Laravel Fortify

**Files:**
- Modify: `composer.json` (via composer)
- Create: `config/fortify.php` (via vendor:publish)
- Create: `app/Actions/Fortify/*.php` (via vendor:publish)

- [ ] **Step 1.1: Require Fortify package**

```bash
cd C:\Users\LENOVO\Documents\ecom-shuvo
composer require laravel/fortify
```

Expected: Fortify installed with no errors. `laravel/fortify` appears in `composer.lock`.

- [ ] **Step 1.2: Publish Fortify config + actions**

```bash
php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
```

Expected output: Published `config/fortify.php` and `app/Actions/Fortify/` files.

- [ ] **Step 1.3: Verify design files were NOT touched**

```bash
git diff --name-only
```

If `resources/css/app.css` or `resources/js/app.js` appears in the diff, immediately run:
```bash
git checkout -- resources/css/app.css resources/js/app.js
```

- [ ] **Step 1.4: Commit the install**

```bash
git add composer.json composer.lock config/fortify.php app/Actions/Fortify/
git -c user.name="Claude" -c user.email="noreply@anthropic.com" commit -m "chore: install laravel/fortify, publish config and actions"
```

---

## Task 2 — Configure Fortify (`config/fortify.php` + FortifyServiceProvider)

**Files:**
- Modify: `config/fortify.php`
- Create: `app/Providers/FortifyServiceProvider.php`
- Modify: `bootstrap/providers.php`

- [ ] **Step 2.1: Edit `config/fortify.php`**

Open `config/fortify.php`. Find the `features` array and replace it so only the needed features are enabled. Also set `home` to `'/account'`. The relevant sections should look like this after editing:

```php
'home' => '/account',

'features' => [
    Features::registration(),
    Features::resetPasswords(),
    Features::updateProfileInformation(),
    Features::updatePasswords(),
    // Features::emailVerification(),
    // Features::twoFactorAuthentication([...]),
],
```

Make sure `'views' => true` is present (it should be by default after publish). Leave all other config values as published.

- [ ] **Step 2.2: Create `app/Providers/FortifyServiceProvider.php`**

```php
<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Point Fortify at the existing Shuvo-styled views
        Fortify::loginView(fn () => view('pages.login'));
        Fortify::registerView(fn () => view('pages.register'));
        Fortify::requestPasswordResetLinkView(fn () => view('pages.forgot-password'));
        Fortify::resetPasswordView(fn ($request) => view('pages.reset-password', ['request' => $request]));

        // Rate limiter for login
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = strtolower($request->input(Fortify::username())) . '|' . $request->ip();
            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
```

- [ ] **Step 2.3: Register the provider in `bootstrap/providers.php`**

Replace the contents of `bootstrap/providers.php`:

```php
<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
];
```

- [ ] **Step 2.4: Verify config loads**

```bash
php artisan config:clear && php artisan route:list | grep login
```

Expected: `/login` GET and POST routes visible in the list, served by Fortify.

---

## Task 3 — Implement `CreateNewUser` action

**Files:**
- Modify: `app/Actions/Fortify/CreateNewUser.php`

The register form sends `first_name` and `last_name` as separate fields (existing design kept). The action merges them into `name`.

- [ ] **Step 3.1: Replace `app/Actions/Fortify/CreateNewUser.php`**

```php
<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name'  => ['required', 'string', 'max:80'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'password'   => ['required', 'confirmed', Password::defaults()],
        ])->validate();

        return User::create([
            'name'     => trim($input['first_name'] . ' ' . $input['last_name']),
            'email'    => $input['email'],
            'phone'    => $input['phone'] ?? null,
            'password' => Hash::make($input['password']),
        ]);
    }
}
```

- [ ] **Step 3.2: Implement the other three actions**

Replace `app/Actions/Fortify/UpdateUserProfileInformation.php`:

```php
<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ])->validateWithBag('updateProfileInformation');

        $user->forceFill([
            'name'  => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
        ])->save();
    }
}
```

Replace `app/Actions/Fortify/UpdateUserPassword.php`:

```php
<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password'         => ['required', 'string', Password::defaults(), 'confirmed'],
        ], [
            'current_password.current_password' => __('The provided password does not match your current password.'),
        ])->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
```

Replace `app/Actions/Fortify/ResetUserPassword.php`:

```php
<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    public function reset(User $user, array $input): void
    {
        Validator::make($input, [
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
```

- [ ] **Step 3.3: Check for syntax errors**

```bash
php artisan config:clear 2>&1 | head -5
```

Expected: No errors.

---

## Task 4 — Models + Migrations (Address + Wishlist)

**Files:**
- Create: `database/migrations/2026_06_08_000001_create_addresses_table.php`
- Create: `database/migrations/2026_06_08_000002_create_wishlists_table.php`
- Create: `app/Models/Address.php`
- Create: `app/Models/Wishlist.php`
- Modify: `app/Models/User.php`

- [ ] **Step 4.1: Create addresses migration**

Create `database/migrations/2026_06_08_000001_create_addresses_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('line');
            $table->string('city');
            $table->string('thana')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
```

- [ ] **Step 4.2: Create wishlists migration**

Create `database/migrations/2026_06_08_000002_create_wishlists_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('product_id');
            $table->unique(['user_id', 'product_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
```

Note: `product_id` is intentionally NOT a FK because products live in the Catalog support class (flat PHP array), not a DB table. If products move to DB in B3/B4, add the FK then.

- [ ] **Step 4.3: Create `app/Models/Address.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = ['user_id', 'name', 'phone', 'line', 'city', 'thana', 'is_default'];

    protected $casts = ['is_default' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

- [ ] **Step 4.4: Create `app/Models/Wishlist.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    protected $fillable = ['user_id', 'product_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

- [ ] **Step 4.5: Update `app/Models/User.php` — add relationships**

Add these two methods inside the `User` class, after the `casts()` method:

```php
public function addresses(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(Address::class)->orderByDesc('is_default');
}

public function wishlists(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(Wishlist::class);
}
```

- [ ] **Step 4.6: Run migrations**

```bash
php artisan migrate --force
```

Expected: `addresses` and `wishlists` tables created with no errors. The existing tables should remain untouched.

---

## Task 5 — Routes

**Files:**
- Modify: `routes/web.php`
- Modify: `app/Http/Controllers/PageController.php`
- Create: `app/Http/Controllers/AccountController.php`
- Create: `app/Http/Controllers/AddressController.php`
- Create: `app/Http/Controllers/WishlistController.php`

- [ ] **Step 5.1: Create `AccountController`**

Create `app/Http/Controllers/AccountController.php`:

```php
<?php

namespace App\Http\Controllers;

class AccountController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $user      = auth()->user();
        $addresses = $user->addresses()->get();
        $wishCount = $user->wishlists()->count();

        return view('pages.account', compact('user', 'addresses', 'wishCount'));
    }
}
```

- [ ] **Step 5.2: Create `AddressController`**

Create `app/Http/Controllers/AddressController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'name'  => 'required|string|max:120',
            'phone' => 'nullable|string|max:20',
            'line'  => 'required|string|max:255',
            'city'  => 'required|string|max:100',
            'thana' => 'nullable|string|max:100',
        ]);

        $user = auth()->user();

        // If this is the first address, make it default
        $isDefault = $user->addresses()->count() === 0;

        $user->addresses()->create(array_merge($data, ['is_default' => $isDefault]));

        return redirect()->route('account')->with('success', 'Address added.');
    }

    public function destroy(Address $address): \Illuminate\Http\RedirectResponse
    {
        abort_if($address->user_id !== auth()->id(), 403);
        $address->delete();
        return redirect()->route('account')->with('success', 'Address removed.');
    }

    public function setDefault(Address $address): \Illuminate\Http\RedirectResponse
    {
        abort_if($address->user_id !== auth()->id(), 403);

        auth()->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('account')->with('success', 'Default address updated.');
    }
}
```

- [ ] **Step 5.3: Create `WishlistController`**

Create `app/Http/Controllers/WishlistController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class WishlistController extends Controller
{
    public function toggle(int $productId): JsonResponse
    {
        $user     = auth()->user();
        $existing = $user->wishlists()->where('product_id', $productId)->first();

        if ($existing) {
            $existing->delete();
            $wished = false;
        } else {
            $user->wishlists()->create(['product_id' => $productId]);
            $wished = true;
        }

        $count = $user->wishlists()->count();

        return response()->json(['wished' => $wished, 'count' => $count]);
    }
}
```

- [ ] **Step 5.4: Update `PageController` — remove login/register methods, update wishlist**

Remove the `login()` and `register()` methods from `PageController`. Update `account()` to redirect to `AccountController`. Update `wishlist()` so authenticated users get DB-seeded wish IDs. The full updated file:

```php
<?php

namespace App\Http\Controllers;

use App\Support\Catalog;

class PageController extends Controller
{
    public function about(): \Illuminate\View\View
    {
        return view('pages.about');
    }

    public function contact(): \Illuminate\View\View
    {
        return view('pages.contact');
    }

    public function blog(): \Illuminate\View\View
    {
        return view('pages.blog');
    }

    public function blogPost(string $slug): \Illuminate\View\View
    {
        return view('pages.blog-post', ['slug' => $slug]);
    }

    public function privacy(): \Illuminate\View\View
    {
        return view('pages.privacy');
    }

    public function terms(): \Illuminate\View\View
    {
        return view('pages.terms');
    }

    public function wishlist(): \Illuminate\View\View
    {
        $products    = Catalog::products();
        $wishedIds   = auth()->check()
            ? auth()->user()->wishlists()->pluck('product_id')->toArray()
            : [];

        return view('pages.wishlist', compact('products', 'wishedIds'));
    }

    public function track(): \Illuminate\View\View
    {
        return view('pages.track');
    }
}
```

- [ ] **Step 5.5: Update `routes/web.php`**

Replace the entire file content:

```php
<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/shop', [CatalogController::class, 'shop'])->name('shop');
Route::get('/category/{slug}', [CatalogController::class, 'category'])->name('category');
Route::get('/product/{id}', [CatalogController::class, 'product'])->whereNumber('id')->name('product');
Route::get('/checkout', [CatalogController::class, 'checkout'])->name('checkout');

// Marketing / static
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [PageController::class, 'blogPost'])->name('blog.post');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// Public account pages (Fortify owns GET /login, GET /register, POST /login, POST /register, POST /logout)
Route::get('/track', [PageController::class, 'track'])->name('track');

// Auth-protected pages
Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::get('/wishlist', [PageController::class, 'wishlist'])->name('wishlist');

    // Addresses
    Route::post('/account/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::delete('/account/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/account/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');

    // Wishlist toggle (AJAX)
    Route::post('/wishlist/toggle/{productId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

Route::fallback(fn () => response()->view('errors.404', [], 404));
```

- [ ] **Step 5.6: Verify routes compile**

```bash
php artisan route:list | grep -E "login|register|account|wishlist|address"
```

Expected: GET+POST `/login`, GET+POST `/register`, GET+POST `/logout` (Fortify), GET `/account` (auth), GET `/wishlist` (auth), POST `/account/addresses` (auth), POST `/wishlist/toggle/{productId}` (auth).

---

## Task 6 — Wire Auth Blade Views

**Files:**
- Modify: `resources/views/pages/login.blade.php`
- Modify: `resources/views/pages/register.blade.php`
- Create: `resources/views/pages/forgot-password.blade.php`
- Create: `resources/views/pages/reset-password.blade.php`

- [ ] **Step 6.1: Update `login.blade.php`**

Replace the `<form>` tag and its inner contents. Keep the surrounding `.co-card` and brand mark intact. Change:

```blade
{{-- Login form (action="#" — frontend only) --}}
<form action="#" method="POST" novalidate>
    @csrf

    <div class="field">
        <label for="login-email">Email address</label>
        <input id="login-email" type="email" name="email" placeholder="you@example.com" autocomplete="email" required>
    </div>

    <div class="field">
        <label for="login-password">Password</label>
        <input id="login-password" type="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
    </div>

    {{-- Remember + Forgot row --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;font-size:14px">
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;color:var(--ink-soft);font-size:14px;font-weight:500">
            <input type="checkbox" name="remember" style="width:17px;height:17px;accent-color:var(--green);cursor:pointer">
            Remember me
        </label>
        <a href="#" style="color:var(--green);font-weight:600">Forgot password?</a>
    </div>

    <button type="submit" class="btn btn-primary btn-block btn-lg">
        Sign In
    </button>
</form>
```

To:

```blade
{{-- Session status (e.g. after password reset) --}}
@if (session('status'))
    <div style="background:var(--green-tint);color:var(--green-deep);border-radius:10px;padding:12px 16px;font-size:14px;font-weight:600;margin-bottom:18px">
        {{ session('status') }}
    </div>
@endif

{{-- Login form --}}
<form action="{{ route('login') }}" method="POST" novalidate>
    @csrf

    <div class="field">
        <label for="login-email">Email address</label>
        <input id="login-email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required>
        @error('email')
            <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
        @enderror
    </div>

    <div class="field">
        <label for="login-password">Password</label>
        <input id="login-password" type="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
        @error('password')
            <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
        @enderror
    </div>

    {{-- Remember + Forgot row --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;font-size:14px">
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;color:var(--ink-soft);font-size:14px;font-weight:500">
            <input type="checkbox" name="remember" style="width:17px;height:17px;accent-color:var(--green);cursor:pointer">
            Remember me
        </label>
        <a href="{{ route('password.request') }}" style="color:var(--green);font-weight:600">Forgot password?</a>
    </div>

    <button type="submit" class="btn btn-primary btn-block btn-lg">
        Sign In
    </button>
</form>
```

- [ ] **Step 6.2: Update `register.blade.php`**

Replace the `<form>` tag and inner contents (keep surrounding markup). Change:

```blade
{{-- Register form (action="#" — frontend only) --}}
<form action="#" method="POST" novalidate>
```

To:

```blade
{{-- Register form --}}
<form action="{{ route('register') }}" method="POST" novalidate>
```

After each input, add `@error` blocks. Full updated form body (replace from `@csrf` to `</form>`):

```blade
    @csrf

    {{-- First / Last name row --}}
    <div class="field-row">
        <div class="field">
            <label for="reg-first">First name</label>
            <input id="reg-first" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Rahim" autocomplete="given-name" required>
            @error('first_name')
                <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
            @enderror
        </div>
        <div class="field">
            <label for="reg-last">Last name</label>
            <input id="reg-last" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Ahmed" autocomplete="family-name" required>
            @error('last_name')
                <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="field">
        <label for="reg-email">Email address</label>
        <input id="reg-email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required>
        @error('email')
            <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
        @enderror
    </div>

    <div class="field">
        <label for="reg-phone">Phone number</label>
        <input id="reg-phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" autocomplete="tel">
        @error('phone')
            <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
        @enderror
    </div>

    <div class="field">
        <label for="reg-password">Password</label>
        <input id="reg-password" type="password" name="password" placeholder="Min. 8 characters" autocomplete="new-password" required>
        @error('password')
            <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
        @enderror
    </div>

    <div class="field">
        <label for="reg-confirm">Confirm password</label>
        <input id="reg-confirm" type="password" name="password_confirmation" placeholder="Repeat your password" autocomplete="new-password" required>
    </div>

    {{-- Terms checkbox --}}
    <div style="margin-bottom:20px">
        <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;font-size:14px;color:var(--ink-soft);line-height:1.5;font-weight:500">
            <input type="checkbox" name="terms" style="width:17px;height:17px;margin-top:2px;accent-color:var(--green);cursor:pointer;flex-shrink:0" required>
            I agree to the <a href="{{ route('terms') }}" style="color:var(--green);font-weight:600">Terms &amp; Conditions</a>
        </label>
    </div>

    <button type="submit" class="btn btn-primary btn-block btn-lg">
        Create Account
    </button>
```

- [ ] **Step 6.3: Create `resources/views/pages/forgot-password.blade.php`**

```blade
@extends('layouts.app')
@section('title', 'Forgot Password — Shuvo')

@section('content')

<div class="wrap" style="padding-top:48px;padding-bottom:64px">
    <div class="co-card" style="max-width:440px;margin:0 auto">

        <div style="text-align:center;margin-bottom:28px">
            <div class="brand-mark" style="width:52px;height:52px;border-radius:16px;margin:0 auto 18px;display:grid;place-items:center;background:linear-gradient(145deg,var(--green) 0%,var(--green-deep) 100%)">
                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 2C6 2 3 7 3 12c0 4.4 2.8 8.1 6.7 9.5C11 19.3 12 17.2 12 15c0-3.3-2-6-5-7 2 0 5 1 7 4 2-3 5-4 7-4-3 1-5 3.7-5 7 0 2.2 1 4.3 2.3 6.5C19.2 20.1 22 16.4 22 12c0-5-3-10-10-10z"/>
                </svg>
            </div>
            <h1 style="font-size:clamp(24px,3vw,32px);margin-bottom:8px">Forgot password?</h1>
            <p style="color:var(--ink-soft);font-size:15px;margin:0">Enter your email and we'll send a reset link.</p>
        </div>

        @if (session('status'))
            <div style="background:var(--green-tint);color:var(--green-deep);border-radius:10px;padding:12px 16px;font-size:14px;font-weight:600;margin-bottom:18px">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" novalidate>
            @csrf

            <div class="field">
                <label for="fp-email">Email address</label>
                <input id="fp-email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required>
                @error('email')
                    <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                Send Reset Link
            </button>
        </form>

        <div style="text-align:center;margin-top:20px;font-size:15px;color:var(--ink-soft)">
            <a href="{{ route('login') }}" style="color:var(--green);font-weight:600">Back to Sign In</a>
        </div>

    </div>
</div>

@endsection
```

- [ ] **Step 6.4: Create `resources/views/pages/reset-password.blade.php`**

```blade
@extends('layouts.app')
@section('title', 'Reset Password — Shuvo')

@section('content')

<div class="wrap" style="padding-top:48px;padding-bottom:64px">
    <div class="co-card" style="max-width:440px;margin:0 auto">

        <div style="text-align:center;margin-bottom:28px">
            <div class="brand-mark" style="width:52px;height:52px;border-radius:16px;margin:0 auto 18px;display:grid;place-items:center;background:linear-gradient(145deg,var(--green) 0%,var(--green-deep) 100%)">
                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 2C6 2 3 7 3 12c0 4.4 2.8 8.1 6.7 9.5C11 19.3 12 17.2 12 15c0-3.3-2-6-5-7 2 0 5 1 7 4 2-3 5-4 7-4-3 1-5 3.7-5 7 0 2.2 1 4.3 2.3 6.5C19.2 20.1 22 16.4 22 12c0-5-3-10-10-10z"/>
                </svg>
            </div>
            <h1 style="font-size:clamp(24px,3vw,32px);margin-bottom:8px">Set new password</h1>
            <p style="color:var(--ink-soft);font-size:15px;margin:0">Choose a strong password for your account.</p>
        </div>

        <form action="{{ route('password.update') }}" method="POST" novalidate>
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="field">
                <label for="rp-email">Email address</label>
                <input id="rp-email" type="email" name="email" value="{{ old('email', $request->email) }}" placeholder="you@example.com" autocomplete="email" required>
                @error('email')
                    <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="rp-password">New password</label>
                <input id="rp-password" type="password" name="password" placeholder="Min. 8 characters" autocomplete="new-password" required>
                @error('password')
                    <span style="color:var(--sale);font-size:13px;margin-top:4px;display:block">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="rp-confirm">Confirm new password</label>
                <input id="rp-confirm" type="password" name="password_confirmation" placeholder="Repeat your password" autocomplete="new-password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                Reset Password
            </button>
        </form>

    </div>
</div>

@endsection
```

---

## Task 7 — Layout: CSRF meta + Auth JS globals

**Files:**
- Modify: `resources/views/layouts/app.blade.php`

- [ ] **Step 7.1: Add CSRF meta tag and auth globals to layout `<head>`**

In `resources/views/layouts/app.blade.php`, replace:

```blade
    @vite(['resources/css/app.css', 'resources/js/app.js'])
```

With:

```blade
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.AUTH = {{ auth()->check() ? 'true' : 'false' }};
        window.WISHLIST = @json(auth()->check() ? auth()->user()->wishlists()->pluck('product_id') : []);
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
```

---

## Task 8 — Header auth state

**Files:**
- Modify: `resources/views/partials/header.blade.php`

- [ ] **Step 8.1: Update the "Sign in" icon-btn in the header**

Find this block in `resources/views/partials/header.blade.php`:

```blade
                {{-- Sign in --}}
                <a href="{{ route('login') }}" class="icon-btn" title="Sign in" aria-label="Sign in to your account">
                    <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                        <path d="M5 20a7 7 0 0 1 14 0"/>
                    </svg>
                    <span class="icon-label">Sign in</span>
                </a>
```

Replace it with:

```blade
                {{-- Account / Sign in --}}
                @auth
                <a href="{{ route('account') }}" class="icon-btn" title="My Account" aria-label="My Account">
                    <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                        <path d="M5 20a7 7 0 0 1 14 0"/>
                    </svg>
                    <span class="icon-label">{{ explode(' ', auth()->user()->name)[0] }}</span>
                </a>
                <form method="POST" action="/logout" style="display:inline;margin:0;padding:0">
                    @csrf
                    <button type="submit" class="icon-btn" title="Logout" aria-label="Logout" style="background:none;border:none;cursor:pointer">
                        <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <span class="icon-label">Logout</span>
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="icon-btn" title="Sign in" aria-label="Sign in to your account">
                    <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                        <path d="M5 20a7 7 0 0 1 14 0"/>
                    </svg>
                    <span class="icon-label">Sign in</span>
                </a>
                @endauth
```

---

## Task 9 — Account dashboard with real data + addresses section

**Files:**
- Modify: `resources/views/pages/account.blade.php`

- [ ] **Step 9.1: Replace dummy header data with real user**

Find this block:

```blade
        <h1>My Account</h1>
        <p class="sub">Welcome back, Guest</p>
```

Replace with:

```blade
        <h1>My Account</h1>
        <p class="sub">Welcome back, {{ explode(' ', $user->name)[0] }}</p>
```

- [ ] **Step 9.2: Replace the profile summary card dummy data**

Find this block (the avatar circle and user info):

```blade
                {{-- Avatar circle --}}
                <div style="width:72px;height:72px;border-radius:50%;background:var(--green-soft);color:var(--green-deep);display:grid;place-items:center;font-family:var(--font-display);font-weight:800;font-size:28px;flex-shrink:0;border:2px solid var(--green-soft)">
                    G
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-family:var(--font-display);font-weight:800;font-size:20px;margin-bottom:4px">Guest User</div>
                    <div style="font-size:14px;color:var(--muted);margin-bottom:2px">guest@shuvo.com</div>
                    <div style="font-size:14px;color:var(--muted)">+880 1700-000000</div>
                </div>
                <a href="#" class="btn btn-ghost" style="flex-shrink:0">
```

Replace with:

```blade
                {{-- Avatar circle --}}
                <div style="width:72px;height:72px;border-radius:50%;background:var(--green-soft);color:var(--green-deep);display:grid;place-items:center;font-family:var(--font-display);font-weight:800;font-size:28px;flex-shrink:0;border:2px solid var(--green-soft)">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-family:var(--font-display);font-weight:800;font-size:20px;margin-bottom:4px">{{ $user->name }}</div>
                    <div style="font-size:14px;color:var(--muted);margin-bottom:2px">{{ $user->email }}</div>
                    @if($user->phone)
                    <div style="font-size:14px;color:var(--muted)">{{ $user->phone }}</div>
                    @endif
                </div>
                <a href="#" class="btn btn-ghost" style="flex-shrink:0">
```

- [ ] **Step 9.3: Update stats row — replace hardcoded numbers**

Find the stats row. Replace the three `co-card` numbers:
- Total Orders: replace `12` with `0` (or `<span>0</span>` — orders come in B4).
- Wishlist Items: replace `5` with `{{ $wishCount }}`.
- Reward Points: keep `0` (placeholder, not yet implemented).

The updated stats grid section:

```blade
            {{-- Stats row --}}
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:18px">
                <div class="co-card" style="text-align:center;padding:20px 16px;margin-bottom:0">
                    <div style="font-family:var(--font-display);font-weight:800;font-size:32px;color:var(--green-deep);margin-bottom:4px">0</div>
                    <div style="font-size:13px;color:var(--muted);font-weight:600;letter-spacing:.03em">Total Orders</div>
                </div>
                <div class="co-card" style="text-align:center;padding:20px 16px;margin-bottom:0">
                    <div style="font-family:var(--font-display);font-weight:800;font-size:32px;color:var(--sale);margin-bottom:4px">{{ $wishCount }}</div>
                    <div style="font-size:13px;color:var(--muted);font-weight:600;letter-spacing:.03em">Wishlist Items</div>
                </div>
                <div class="co-card" style="text-align:center;padding:20px 16px;margin-bottom:0">
                    <div style="font-family:var(--font-display);font-weight:800;font-size:32px;color:var(--honey);margin-bottom:4px">0</div>
                    <div style="font-size:13px;color:var(--muted);font-weight:600;letter-spacing:.03em">Reward Points</div>
                </div>
            </div>
```

- [ ] **Step 9.4: Replace the dummy recent orders with a "no orders yet" placeholder**

Remove the three order rows and the "view all orders" link. Replace the entire Recent Orders co-card content section (from the `<div style="display:grid;grid-template-columns:1.4fr...">` table header onward) with:

```blade
                {{-- No orders yet --}}
                <div style="padding:32px;text-align:center;color:var(--muted)">
                    <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="margin:0 auto 14px;display:block;color:var(--line)">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                        <path d="M9 12h6"/><path d="M9 16h4"/>
                    </svg>
                    <p style="font-size:15px;margin:0">No orders yet.</p>
                </div>
```

- [ ] **Step 9.5: Wire the sidebar Logout link to a real form**

Find the sidebar logout `<a href="#">`:

```blade
            <a href="#" class="fopt" style="padding:12px 20px;border-radius:0;border-left:3px solid transparent;color:var(--sale)">
                <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Logout
            </a>
```

Replace with:

```blade
            <form method="POST" action="/logout" style="margin:0;padding:0">
                @csrf
                <button type="submit" class="fopt" style="width:100%;text-align:left;padding:12px 20px;border-radius:0;border-left:3px solid transparent;color:var(--sale);background:none;border-top:none;border-right:none;border-bottom:none;cursor:pointer;font-size:inherit;font-family:inherit">
                    <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Logout
                </button>
            </form>
```

- [ ] **Step 9.6: Add Addresses section to account dashboard**

After the closing `</div>` of the Recent Orders co-card, add the Addresses section. This goes AFTER the orders card and BEFORE the closing `</div>` of the right column:

```blade
            {{-- Addresses section --}}
            <div class="co-card" style="margin-top:18px">
                <h3 style="font-size:18px;margin-bottom:18px;display:flex;align-items:center;gap:10px">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:var(--green)">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                    </svg>
                    Saved Addresses
                </h3>

                @if(session('success'))
                    <div style="background:var(--green-tint);color:var(--green-deep);border-radius:10px;padding:10px 14px;font-size:13.5px;font-weight:600;margin-bottom:14px">{{ session('success') }}</div>
                @endif

                {{-- Existing addresses --}}
                @forelse($addresses as $addr)
                    <div style="border:1px solid var(--line);border-radius:12px;padding:14px 16px;margin-bottom:12px;display:flex;align-items:flex-start;justify-content:space-between;gap:12px">
                        <div style="flex:1;min-width:0">
                            <div style="font-weight:700;font-size:14.5px;margin-bottom:2px">
                                {{ $addr->name }}
                                @if($addr->is_default)
                                    <span style="font-size:11px;font-weight:700;background:var(--green-tint);color:var(--green-deep);border-radius:999px;padding:2px 8px;margin-left:6px">Default</span>
                                @endif
                            </div>
                            <div style="font-size:13.5px;color:var(--ink-soft)">{{ $addr->line }}, {{ $addr->thana ? $addr->thana.', ' : '' }}{{ $addr->city }}</div>
                            @if($addr->phone)<div style="font-size:13px;color:var(--muted)">{{ $addr->phone }}</div>@endif
                        </div>
                        <div style="display:flex;gap:8px;flex-shrink:0">
                            @if(!$addr->is_default)
                                <form method="POST" action="{{ route('addresses.default', $addr) }}" style="margin:0">
                                    @csrf
                                    <button type="submit" style="font-size:12px;font-weight:600;color:var(--green);background:none;border:1px solid var(--green);border-radius:8px;padding:5px 10px;cursor:pointer">Set Default</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('addresses.destroy', $addr) }}" style="margin:0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="font-size:12px;font-weight:600;color:var(--sale);background:none;border:1px solid var(--sale);border-radius:8px;padding:5px 10px;cursor:pointer" onclick="return confirm('Remove this address?')">Remove</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p style="font-size:14.5px;color:var(--muted);margin-bottom:16px">No saved addresses yet.</p>
                @endforelse

                {{-- Add new address form --}}
                <details style="margin-top:8px">
                    <summary style="cursor:pointer;font-size:14px;font-weight:700;color:var(--green);list-style:none;display:flex;align-items:center;gap:6px">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        Add New Address
                    </summary>
                    <form method="POST" action="{{ route('addresses.store') }}" style="margin-top:16px">
                        @csrf
                        <div class="field-row">
                            <div class="field">
                                <label>Full Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Rahim Ahmed" required>
                                @error('name')<span style="color:var(--sale);font-size:13px;display:block;margin-top:4px">{{ $message }}</span>@enderror
                            </div>
                            <div class="field">
                                <label>Phone</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="01XXXXXXXXX">
                            </div>
                        </div>
                        <div class="field">
                            <label>Address Line</label>
                            <input type="text" name="line" value="{{ old('line') }}" placeholder="House 12, Road 4, Dhanmondi" required>
                            @error('line')<span style="color:var(--sale);font-size:13px;display:block;margin-top:4px">{{ $message }}</span>@enderror
                        </div>
                        <div class="field-row">
                            <div class="field">
                                <label>City</label>
                                <input type="text" name="city" value="{{ old('city') }}" placeholder="Dhaka" required>
                                @error('city')<span style="color:var(--sale);font-size:13px;display:block;margin-top:4px">{{ $message }}</span>@enderror
                            </div>
                            <div class="field">
                                <label>Thana / Upazila</label>
                                <input type="text" name="thana" value="{{ old('thana') }}" placeholder="Dhanmondi">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-top:4px">Save Address</button>
                    </form>
                </details>

            </div>
```

---

## Task 10 — Wishlist page: server-side for auth users

**Files:**
- Modify: `resources/views/pages/wishlist.blade.php`

The page now receives `$wishedIds` (array of product_ids from DB for auth users, empty for guests). Alpine's `$store.shop.wish` is still seeded from `window.WISHLIST` (set in layout). The server-rendered cards are shown for auth users; guests still rely on Alpine-only behavior.

- [ ] **Step 10.1: Update `wishlist.blade.php` to use `$wishedIds` for server-side seeding**

Replace the entire product grid section. Find:

```blade
        {{-- Product grid — each card shown only if wishlisted --}}
        <div class="grid-4">
            @foreach ($products as $p)
                <div x-show="$store.shop.isWished({{ $p['id'] }})" x-cloak>
                    <x-product-card :product="$p" />
                </div>
            @endforeach
        </div>
```

Replace with:

```blade
        {{-- Product grid --}}
        {{-- Auth users: show only products that are in their DB wishlist (seeded to window.WISHLIST) --}}
        {{-- Guest users: Alpine drives show/hide from local store --}}
        <div class="grid-4">
            @foreach ($products as $p)
                <div x-show="$store.shop.isWished({{ $p['id'] }})" x-cloak>
                    <x-product-card :product="$p" />
                </div>
            @endforeach
        </div>
```

Note: No structural change is needed here because `window.WISHLIST` now seeds the Alpine store with the DB ids on page load (via the `init()` added to the store in Task 11). The existing `x-show` / `isWished` logic works for both auth and guest.

---

## Task 11 — JS: init store from window.WISHLIST + persistWish helper

**Files:**
- Modify: `resources/js/app.js`

- [ ] **Step 11.1: Add `init()` and `persistWish` to `app.js`**

In `app.js`, update the `Alpine.store('shop', { ... })` block. Add an `init()` method immediately after the opening brace of the store object (before `FREE_GIFT_THRESHOLD`):

```js
  // Seed wish from server (set in layout for auth users)
  init() {
    if (window.WISHLIST && window.WISHLIST.length > 0) {
      this.wish = window.WISHLIST.map(Number);
    }
  },
```

After the existing `Alpine.start();` line at the bottom of the file, add:

```js
// Persist wishlist toggle to server for authenticated users
window.persistWish = function(productId) {
  if (!window.AUTH) return;
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  fetch(`/wishlist/toggle/${productId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': token,
      'Accept': 'application/json',
    },
  }).catch(() => {}); // silent fail — UI already updated optimistically
};
```

**IMPORTANT:** The existing contents of `app.js` must remain intact. Only add the `init()` method at the top of the store and `window.persistWish` after `Alpine.start()`. Do NOT remove or modify any existing methods.

- [ ] **Step 11.2: Update `<x-product-card>` heart button to also call `persistWish`**

In `resources/views/components/product-card.blade.php`, find:

```blade
    <button class="pcard-wish"
            :class="$store.shop.isWished({{ $p['id'] }}) ? 'on' : ''"
            @click.stop="$store.shop.toggleWish({{ $p['id'] }})"
            aria-label="Add to wishlist">
```

Replace with:

```blade
    <button class="pcard-wish"
            :class="$store.shop.isWished({{ $p['id'] }}) ? 'on' : ''"
            @click.stop="$store.shop.toggleWish({{ $p['id'] }}); window.persistWish({{ $p['id'] }})"
            aria-label="Add to wishlist">
```

---

## Task 12 — PHPUnit config + AuthTest

**Files:**
- Modify: `phpunit.xml`
- Modify: `database/factories/UserFactory.php`
- Modify: `tests/Feature/AccountPagesTest.php`
- Create: `tests/Feature/AuthTest.php`

### Test Database Strategy

The existing tests (`HomePageTest`, `ShopPageTest`, etc.) hit the seeded SQLite file DB and do NOT use `RefreshDatabase`. They must keep working.

`AuthTest` needs an isolated DB to create/destroy users without polluting the file DB. **Solution:** Use `DatabaseTransactions` (not `RefreshDatabase`) in `AuthTest` — every test is wrapped in a transaction that's rolled back. This works with the FILE db (no in-memory needed) and does not wipe seeded product data.

The SQLite comment lines in `phpunit.xml` can stay commented out. Instead, ensure the test sqlite file exists.

- [ ] **Step 12.1: Ensure the SQLite test database file exists**

```bash
php artisan migrate --force
```

Confirm `database/database.sqlite` exists and is migrated.

- [ ] **Step 12.2: Update `UserFactory` to include `phone`**

In `database/factories/UserFactory.php`, update `definition()`:

```php
public function definition(): array
{
    return [
        'name'              => fake()->name(),
        'email'             => fake()->unique()->safeEmail(),
        'email_verified_at' => now(),
        'password'          => static::$password ??= Hash::make('password'),
        'phone'             => fake()->numerify('017########'),
        'remember_token'    => Str::random(10),
    ];
}
```

- [ ] **Step 12.3: Update `AccountPagesTest` — account and wishlist now redirect for guests**

The existing `AccountPagesTest` asserts `$this->get('/account')->assertOk()`. Now `/account` requires auth, so it redirects. Update it:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccountPagesTest extends TestCase
{
    public function test_pages_render(): void
    {
        $this->get('/login')->assertOk()->assertSee('Welcome back');
        $this->get('/register')->assertOk()->assertSee('Create your account');
        $this->get('/track')->assertOk()->assertSee('Track Your Order');
    }

    public function test_account_redirects_for_guest(): void
    {
        $this->get('/account')->assertRedirect('/login');
    }

    public function test_wishlist_redirects_for_guest(): void
    {
        $this->get('/wishlist')->assertRedirect('/login');
    }
}
```

- [ ] **Step 12.4: Create `tests/Feature/AuthTest.php`**

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_register_creates_user(): void
    {
        $response = $this->post('/register', [
            'first_name'            => 'Test',
            'last_name'             => 'User',
            'email'                 => 'authtest_unique_' . time() . '@example.com',
            'phone'                 => '01700000000',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'authtest_unique_' . time() . '@example.com']);
    }

    public function test_login_works(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password123',
        ])->assertRedirect();

        $this->assertAuthenticated();
    }

    public function test_account_requires_auth(): void
    {
        $this->get('/account')->assertRedirect('/login');
    }

    public function test_auth_user_sees_account(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/account')->assertOk();
    }
}
```

Note on `test_register_creates_user`: because `DatabaseTransactions` rolls back after each test, there is a subtle issue — the `assertDatabaseHas` check at the END of the test may fail if the transaction has already been partially rolled back. To be safe, capture the email BEFORE the post:

Actually update the test so the email is captured in a variable:

```php
    public function test_register_creates_user(): void
    {
        $email = 'authtest_' . uniqid() . '@example.com';

        $this->post('/register', [
            'first_name'            => 'Test',
            'last_name'             => 'User',
            'email'                 => $email,
            'phone'                 => '01700000000',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', ['email' => $email]);
    }
```

Use this version.

---

## Task 13 — Run full test suite

**Files:** (no changes)

- [ ] **Step 13.1: Clear caches and run tests**

```bash
php artisan config:clear && php artisan route:clear && php artisan view:clear
php artisan test --verbose
```

Expected: All tests GREEN. If any test fails, diagnose and fix before proceeding.

Common issues and fixes:
- `Route [login] not defined` in old tests → routes/web.php might have a typo; Fortify registers this route automatically.
- `View [pages.forgot-password] not found` → verify the file exists at `resources/views/pages/forgot-password.blade.php`.
- `Call to undefined method ... wishlists()` → verify `User::wishlists()` relationship was added and migration ran.
- Existing tests failing on `/account` assertOk → `AccountPagesTest` was already updated in Task 12.3.

---

## Task 14 — Asset build + smoke test

**Files:** (no changes)

- [ ] **Step 14.1: Build assets**

```bash
npm run build
```

Expected: Build completes without errors. `public/build/` contains updated JS/CSS. Confirm `resources/css/app.css` and `resources/js/app.js` are not in the git diff.

- [ ] **Step 14.2: Start server in background (port 8776)**

```bash
php artisan serve --port=8776 &
```

- [ ] **Step 14.3: Smoke test key routes**

```bash
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8776/login
# Expected: 200

curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8776/register
# Expected: 200

curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8776/forgot-password
# Expected: 200

curl -s -o /dev/null -w "%{http_code}" -L http://127.0.0.1:8776/account
# Expected: 302 redirect for guest (curl -L follows, ends at /login = 200)
```

- [ ] **Step 14.4: Stop background server**

```bash
kill $(lsof -t -i:8776) 2>/dev/null || true
```

---

## Task 15 — Commit

- [ ] **Step 15.1: Verify no design files were clobbered**

```bash
git diff --name-only resources/css/app.css resources/js/app.js
```

Expected: No output (files are clean — only the `init()` and `persistWish` additions to `app.js` which are intentional).

- [ ] **Step 15.2: Stage and commit**

```bash
git add -A
git -c user.name="Claude" -c user.email="noreply@anthropic.com" commit -m "Backend B2: customer auth via Fortify (themed views, profile, addresses, persistent wishlist)"
```

Expected: Commit created with SHA output.

---

## Self-Review Checklist

| Requirement | Task |
|---|---|
| Install Fortify (not Breeze) | Task 1 |
| Publish config + actions | Task 1 |
| Register FortifyServiceProvider in bootstrap/providers.php | Task 2.3 |
| Fortify view callbacks → existing Shuvo views | Task 2.2 |
| config/fortify.php: correct features, home='/account' | Task 2.1 |
| CreateNewUser merges first_name+last_name, adds phone | Task 3.1 |
| Other Fortify actions implemented | Task 3.2 |
| addresses migration + model | Task 4 |
| wishlists migration + model (no product FK — products are not in DB) | Task 4 |
| User hasMany addresses + wishlists | Task 4.5 |
| php artisan migrate --force | Task 4.6 |
| Remove duplicate GET /login, GET /register from routes | Task 5.5 |
| Protect /account and /wishlist with auth middleware | Task 5.5 |
| AddressController: store/destroy/setDefault | Task 5.2 |
| WishlistController: toggle returns JSON | Task 5.3 |
| AccountController: serves real user+addresses+wishCount | Task 5.1 |
| login.blade.php wired to route('login'), @error, old() | Task 6.1 |
| register.blade.php wired to route('register'), first+last name, @error | Task 6.2 |
| forgot-password.blade.php created (Shuvo styled) | Task 6.3 |
| reset-password.blade.php created (Shuvo styled) | Task 6.4 |
| Layout: CSRF meta + window.AUTH + window.WISHLIST | Task 7 |
| Header: @auth → account link + logout; @guest → Sign in | Task 8 |
| account.blade.php: real user name/email/phone, real wishCount, no-orders placeholder, logout form, addresses section | Task 9 |
| wishlist.blade.php: Alpine x-show still works, DB-seeded via window.WISHLIST | Task 10 |
| app.js: init() seeds wish from window.WISHLIST; window.persistWish() | Task 11.1 |
| product-card: heart @click calls persistWish | Task 11.2 |
| app.css/app.js NOT overwritten (only additive app.js changes) | Tasks 1+15 |
| DatabaseTransactions test strategy keeps existing tests green | Task 12 |
| AccountPagesTest updated for new redirect behavior | Task 12.3 |
| AuthTest: register, login, account-requires-auth, auth-user-sees-account | Task 12.4 |
| Full test suite green | Task 13 |
| npm run build green | Task 14 |
| Smoke test /login=200, /register=200, /account guest=302 | Task 14 |
| Commit with specified message | Task 15 |
