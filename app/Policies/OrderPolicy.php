<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Admins can view any order.
     * Authenticated users can only view their own orders.
     */
    public function view(User $user, Order $order): bool
    {
        if ($user->is_admin) {
            return true;
        }

        return $order->user_id !== null && $user->id === $order->user_id;
    }
}
