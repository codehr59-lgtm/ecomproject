<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ['name', 'display_name', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function hasPermission(string $permission): bool
    {
        $permissions = $this->permissions ?? [];

        if (in_array('*', $permissions, true)) {
            return true;
        }

        return in_array($permission, $permissions, true);
    }

    public static function allPermissions(): array
    {
        return [
            'Products' => [
                'products.view'   => 'View Products',
                'products.create' => 'Create Products',
                'products.edit'   => 'Edit Products',
                'products.delete' => 'Delete Products',
            ],
            'Orders' => [
                'orders.view'   => 'View Orders',
                'orders.edit'   => 'Edit Orders',
                'orders.delete' => 'Delete Orders',
            ],
            'Customers' => [
                'customers.view' => 'View Customers',
                'customers.edit' => 'Edit Customers',
            ],
            'Coupons' => [
                'coupons.view'   => 'View Coupons',
                'coupons.create' => 'Create Coupons',
                'coupons.edit'   => 'Edit Coupons',
                'coupons.delete' => 'Delete Coupons',
            ],
            'CMS' => [
                'cms.pages'         => 'Manage Pages',
                'cms.blog'          => 'Manage Blog',
                'cms.banners'       => 'Manage Banners',
                'cms.sliders'       => 'Manage Sliders',
                'cms.menus'         => 'Manage Menus',
                'cms.faqs'          => 'Manage FAQs',
                'cms.testimonials'  => 'Manage Testimonials',
                'cms.popups'        => 'Manage Popups',
                'cms.announcements' => 'Manage Announcements',
            ],
            'Settings' => [
                'settings.payment'   => 'Payment Settings',
                'settings.marketing' => 'Marketing Settings',
                'settings.homepage'  => 'Homepage Settings',
                'settings.theme'     => 'Theme Settings',
                'settings.general'   => 'General Settings',
            ],
            'Admin' => [
                'admin.roles'         => 'Manage Roles',
                'admin.logs'          => 'View Logs',
                'admin.backups'       => 'Manage Backups',
                'admin.notifications' => 'View Notifications',
                'admin.reports'       => 'View Reports',
            ],
        ];
    }
}
