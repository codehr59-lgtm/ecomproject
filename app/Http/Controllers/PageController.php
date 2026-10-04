<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Order;
use App\Models\Page;
use App\Models\Testimonial;

class PageController extends Controller
{
    public function cmsPage(string $slug): \Illuminate\View\View
    {
        $page = Page::where('slug', $slug)->where('is_published', true)->first();

        if (! $page) {
            $defaultPages = [
                'support-center' => ['title' => 'Support Center', 'body' => '<h2>Customer Support Center</h2><p>Need assistance with an existing order or product inquiry? Our dedicated team is available 9:00 AM to 10:00 PM every day.</p><ul><li><strong>Hotline:</strong> Call our customer care number.</li><li><strong>WhatsApp:</strong> Instant chat support for quick inquiries.</li><li><strong>Email:</strong> support@masalavalley.com</li></ul>'],
                'how-to-order'   => ['title' => 'How to Order', 'body' => '<h2>How to Order</h2><ol><li>Browse products and add your preferred items to cart.</li><li>Click checkout and enter your delivery address.</li><li>Choose Cash on Delivery (COD) or Online Payment and confirm your order.</li></ol>'],
                'payment'        => ['title' => 'Payment Methods', 'body' => '<h2>Payment Methods & Security</h2><p>We provide 100% secure payment options across Bangladesh:</p><ul><li><strong>Cash on Delivery (COD):</strong> Pay in cash when your parcel is delivered.</li><li><strong>bKash / Nagad / Rocket:</strong> Instant mobile financial service payments.</li><li><strong>Credit / Debit Cards:</strong> Visa, Mastercard, DBBL Nexus via secure gateway.</li></ul>'],
                'shipping'       => ['title' => 'Shipping & Delivery', 'body' => '<h2>Shipping & Delivery Information</h2><p>We deliver nationwide across Bangladesh through Steadfast Courier, Pathao, and RedX.</p><ul><li><strong>Inside Dhaka:</strong> 24-48 hours (৳60, free delivery over ৳1,500).</li><li><strong>Outside Dhaka:</strong> 48-72 hours (৳120).</li></ul>'],
                'happy-return'   => ['title' => 'Happy Return', 'body' => '<h2>Happy Return Guarantee</h2><p>Shop with confidence! You can inspect your parcel in front of the delivery agent. If any item is damaged or not as expected, return it instantly at your doorstep.</p>'],
                'refund-policy'  => ['title' => 'Refund Policy', 'body' => '<h2>Refund Policy</h2><p>Approved refunds for online payments are processed within 2-5 working days for bKash/Nagad and 5-10 days for cards.</p>'],
                'exchange'       => ['title' => 'Exchange Policy', 'body' => '<h2>Exchange Policy</h2><p>Received a damaged or incorrect product? Notify us within 48 hours for a free replacement.</p>'],
                'cancellation'   => ['title' => 'Cancellation Policy', 'body' => '<h2>Cancellation Policy</h2><p>You can cancel your order anytime before it has been dispatched from our warehouse by contacting our support team.</p>'],
                'pre-order'      => ['title' => 'Pre-Order Policy', 'body' => '<h2>Pre-Order Terms & Guidelines</h2><p>Pre-order seasonal organic crops, fresh dates, and specialized spices to guarantee stock upon harvest arrival.</p>'],
                'extra-discount' => ['title' => 'Extra Discount & Offers', 'body' => '<h2>Extra Discounts & Special Offers</h2><p>Enjoy extra savings through combo bundles, discount vouchers, and free shipping promotions on Masala Valley.</p>'],
                'careers'        => ['title' => 'Careers', 'body' => '<h2>Join the Masala Valley Team</h2><p>We are constantly expanding! Send your CV and cover letter to <strong>contact@masalavalley.com</strong>.</p>'],
            ];

            if (isset($defaultPages[$slug])) {
                $page = Page::create([
                    'title'        => $defaultPages[$slug]['title'],
                    'slug'         => $slug,
                    'body'         => $defaultPages[$slug]['body'],
                    'meta_title'   => $defaultPages[$slug]['title'] . ' — ' . \App\Models\Setting::get('site_name', 'Masala Valley'),
                    'is_published' => true,
                ]);
            } else {
                abort(404);
            }
        }

        return view('pages.cms-page', compact('page'));
    }

    public function faq(): \Illuminate\View\View
    {
        $faqs = Faq::active()->get()->groupBy('category');
        return view('pages.faq', compact('faqs'));
    }

    public function about(): \Illuminate\View\View
    {
        $page = Page::where('slug', 'about')->where('is_published', true)->first();
        if ($page) {
            return view('pages.cms-page', compact('page'));
        }
        return view('pages.about');
    }

    public function contact(): \Illuminate\View\View
    {
        return view('pages.contact');
    }

    public function blog(): \Illuminate\View\View
    {
        $posts = BlogPost::where('is_published', true)
            ->orderByDesc('published_at')
            ->get();
        return view('pages.blog', compact('posts'));
    }

    public function blogPost(string $slug): \Illuminate\View\View
    {
        $post = BlogPost::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return view('pages.blog-post', compact('post'));
    }

    public function privacy(): \Illuminate\View\View
    {
        $page = Page::where('slug', 'privacy')->where('is_published', true)->first();
        if ($page) {
            return view('pages.cms-page', compact('page'));
        }
        return view('pages.privacy');
    }

    public function terms(): \Illuminate\View\View
    {
        $page = Page::where('slug', 'terms')->where('is_published', true)->first();
        if ($page) {
            return view('pages.cms-page', compact('page'));
        }
        return view('pages.terms');
    }

    public function account(): \Illuminate\View\View
    {
        $user = auth()->user();
        $addresses = $user->addresses()->orderByDesc('is_default')->orderBy('created_at')->get();
        $orders = $user->orders()->with('items')->latest()->get();
        return view('pages.account', compact('user', 'addresses', 'orders'));
    }

    public function wishlist(): \Illuminate\View\View
    {
        $user = auth()->user();
        $products = $user->wishlists()
            ->with('product.category')
            ->get()
            ->pluck('product')
            ->filter()
            ->map(fn ($p) => $p->toCardArray())
            ->values();

        return view('pages.wishlist', ['products' => $products, 'serverWishlist' => true]);
    }

    public function track(\Illuminate\Http\Request $request): \Illuminate\View\View
    {
        $number = $request->query('number');
        $order  = null;

        if ($number) {
            $order = Order::with(['items.product', 'statusHistories'])->where('number', $number)->first();
        }

        return view('pages.track', compact('order', 'number'));
    }
}
