<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;

class WishlistController extends Controller
{
    /**
     * Toggle a product in the authenticated user's wishlist.
     * Returns JSON { wished: bool, count: int }.
     */
    public function toggle(Product $product): JsonResponse
    {
        $user = auth()->user();

        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $wished = false;
        } else {
            Wishlist::create([
                'user_id'    => $user->id,
                'product_id' => $product->id,
            ]);
            $wished = true;
        }

        $count = $user->wishlists()->count();

        return response()->json(['wished' => $wished, 'count' => $count]);
    }
}
