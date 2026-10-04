<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about',
                'body' => '<h2>Welcome to Masala Valley</h2>
<p>At <strong>Masala Valley</strong>, we are committed to delivering 100% pure, natural, and premium organic groceries, aromatic spices, raw honey, premium dates, and pure mustard oil directly to your doorstep across Bangladesh.</p>
<h3>Our Mission</h3>
<p>To provide healthy, authentic, and chemical-free food products sourced directly from farmers and certified suppliers. We believe healthy living begins with wholesome, natural food free from artificial additives or preservatives.</p>
<h3>Why Choose Us?</h3>
<ul>
    <li><strong>100% Authentic & Halal:</strong> Every item is rigorously quality-checked.</li>
    <li><strong>Fast Delivery:</strong> Express delivery across Dhaka and nationwide shipping.</li>
    <li><strong>Customer First:</strong> Dedicated support team ready to assist with orders and questions.</li>
</ul>',
                'meta_title' => 'About Us — Masala Valley',
                'meta_description' => 'Learn more about Masala Valley and our commitment to pure, organic, and halal groceries.',
                'sort' => 1,
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms',
                'body' => '<h2>Terms & Conditions</h2>
<p>Please read these terms and conditions carefully before placing an order on Masala Valley.</p>
<h3>1. Orders and Acceptance</h3>
<p>By placing an order through our website, you warrant that you are legally capable of entering into binding contracts. Orders are subject to product availability and confirmation of order details.</p>
<h3>2. Pricing & Payment</h3>
<p>All prices listed on the site are in Bangladeshi Taka (BDT) and inclusive of applicable taxes. We accept Cash on Delivery (COD), bKash, Nagad, Rocket, and major debit/credit cards.</p>
<h3>3. Delivery & Inspection</h3>
<p>Customers are encouraged to inspect products at the time of delivery before releasing payment to the delivery agent.</p>',
                'meta_title' => 'Terms & Conditions — Masala Valley',
                'meta_description' => 'Terms and conditions for purchasing from Masala Valley.',
                'sort' => 2,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy',
                'body' => '<h2>Privacy Policy</h2>
<p>Your privacy is of the utmost importance to us. This policy outlines how Masala Valley collects, uses, and safeguards your personal information.</p>
<h3>Information We Collect</h3>
<p>We collect essential order details such as your name, delivery address, phone number, and email address solely to process your orders and provide tracking updates.</p>
<h3>Security</h3>
<p>We do not share, sell, or rent your private personal information to any third parties for marketing purposes. All data transmissions are encrypted using standard SSL security protocols.</p>',
                'meta_title' => 'Privacy Policy — Masala Valley',
                'meta_description' => 'How Masala Valley protects and manages your personal data and privacy.',
                'sort' => 3,
            ],
            [
                'title' => 'Careers',
                'slug' => 'careers',
                'body' => '<h2>Join the Masala Valley Team</h2>
<p>We are constantly expanding and looking for passionate, driven individuals who share our vision of bringing pure, organic, and quality food to homes across Bangladesh.</p>
<h3>Open Opportunities</h3>
<ul>
    <li>Customer Support & Relationship Executive</li>
    <li>Supply Chain & Logistics Specialist</li>
    <li>Digital Marketing & Content Creator</li>
    <li>Warehouse & Packaging Associate</li>
</ul>
<p>Interested in working with us? Send your updated CV and cover letter to our HR department at <strong>contact@masalavalley.com</strong>.</p>',
                'meta_title' => 'Careers — Masala Valley',
                'meta_description' => 'Explore career opportunities and job openings at Masala Valley.',
                'sort' => 4,
            ],
            [
                'title' => 'Support Center',
                'slug' => 'support-center',
                'body' => '<h2>Customer Support Center</h2>
<p>Need assistance with an existing order, product inquiry, or delivery status? Our dedicated support team is here to help you every day.</p>
<h3>Contact Channels</h3>
<ul>
    <li><strong>Hotline:</strong> Call us directly at our customer care number.</li>
    <li><strong>WhatsApp:</strong> Quick assistance via WhatsApp chat for instant order inquiries.</li>
    <li><strong>Email:</strong> support@masalavalley.com (response within 24 hours).</li>
</ul>
<p>Our support hours are 9:00 AM to 10:00 PM, 7 days a week.</p>',
                'meta_title' => 'Support Center — Masala Valley',
                'meta_description' => 'Get in touch with Masala Valley Customer Support for quick assistance.',
                'sort' => 5,
            ],
            [
                'title' => 'How to Order',
                'slug' => 'how-to-order',
                'body' => '<h2>How to Place an Order</h2>
<p>Ordering from Masala Valley is quick and effortless in just 4 simple steps:</p>
<ol>
    <li><strong>Browse Products:</strong> Explore our organic groceries, spices, honey, dates, and combo packages.</li>
    <li><strong>Add to Cart:</strong> Choose your preferred weight or variation and click "Add to Cart".</li>
    <li><strong>Checkout:</strong> Click the Cart button, review your items, and proceed to checkout. You can checkout as a guest or create an account.</li>
    <li><strong>Confirm & Relax:</strong> Provide your delivery address and contact number, choose payment method (COD or Online), and confirm. We will take care of the rest!</li>
</ol>',
                'meta_title' => 'How to Order — Masala Valley',
                'meta_description' => 'Easy step-by-step guide on how to purchase products from Masala Valley.',
                'sort' => 6,
            ],
            [
                'title' => 'Payment Methods',
                'slug' => 'payment',
                'body' => '<h2>Payment Methods & Security</h2>
<p>We provide multiple flexible and 100% secure payment options for our customers across Bangladesh:</p>
<ul>
    <li><strong>Cash on Delivery (COD):</strong> Pay in cash when you receive and verify your parcel at your doorstep.</li>
    <li><strong>bKash / Nagad / Rocket:</strong> Instant mobile financial service payments.</li>
    <li><strong>Credit / Debit Cards:</strong> Visa, Mastercard, DBBL Nexus via secure payment gateways.</li>
</ul>
<p>All online transactions are encrypted with 256-bit SSL encryption to guarantee complete financial privacy and security.</p>',
                'meta_title' => 'Payment Methods — Masala Valley',
                'meta_description' => 'Learn about accepted payment methods and payment security at Masala Valley.',
                'sort' => 7,
            ],
            [
                'title' => 'Shipping & Delivery',
                'slug' => 'shipping',
                'body' => '<h2>Shipping & Delivery Information</h2>
<p>We deliver to every district, upazila, and corner of Bangladesh through reliable courier partners (Steadfast Courier, Pathao, and RedX).</p>
<h3>Delivery Timeframes</h3>
<ul>
    <li><strong>Inside Dhaka:</strong> 24 to 48 hours.</li>
    <li><strong>Outside Dhaka / Sub-urban:</strong> 48 to 72 hours.</li>
</ul>
<h3>Delivery Charges</h3>
<ul>
    <li><strong>Inside Dhaka:</strong> ৳60 (Free delivery on orders over ৳1,500).</li>
    <li><strong>Outside Dhaka:</strong> ৳120 standard courier fee.</li>
</ul>',
                'meta_title' => 'Shipping & Delivery Policy — Masala Valley',
                'meta_description' => 'Delivery timeframes, courier partners, and shipping costs at Masala Valley.',
                'sort' => 8,
            ],
            [
                'title' => 'Happy Return',
                'slug' => 'happy-return',
                'body' => '<h2>Happy Return Guarantee</h2>
<p>We want you to shop with complete peace of mind. If you are not satisfied with the quality of any product you receive, you are covered by our <strong>Happy Return</strong> policy.</p>
<h3>Instant Return at Doorstep</h3>
<p>You can check the parcel in front of the delivery agent. If any item is damaged, leaked, or not as expected, you may return it immediately to the delivery person with zero hassle.</p>',
                'meta_title' => 'Happy Return Policy — Masala Valley',
                'meta_description' => 'Customer-first Happy Return policy at Masala Valley.',
                'sort' => 9,
            ],
            [
                'title' => 'Refund Policy',
                'slug' => 'refund-policy',
                'body' => '<h2>Refund Policy</h2>
<p>If you have paid in advance via card, bKash, or Nagad and request a cancellation or approved return, your refund will be processed promptly.</p>
<h3>Refund Timelines</h3>
<ul>
    <li><strong>bKash / Nagad / MFS:</strong> 2 to 5 working days.</li>
    <li><strong>Credit / Debit Cards:</strong> 5 to 10 banking days (depending on your card issuing bank).</li>
</ul>
<p>To request a refund, please contact our support team with your order number and transaction details.</p>',
                'meta_title' => 'Refund Policy — Masala Valley',
                'meta_description' => 'Refund guidelines, terms, and processing timelines at Masala Valley.',
                'sort' => 10,
            ],
            [
                'title' => 'Exchange Policy',
                'slug' => 'exchange',
                'body' => '<h2>Exchange Policy</h2>
<p>Received an incorrect item, wrong size/weight, or damaged package? We will exchange it for free!</p>
<h3>Conditions for Exchange</h3>
<ul>
    <li>Report the issue within 48 hours of delivery with photos of the package.</li>
    <li>The product must be in its original packaging with safety seal intact (unless reported as defective/spoiled).</li>
</ul>
<p>Our courier partner will pick up the item and deliver the replacement at no additional shipping charge to you.</p>',
                'meta_title' => 'Exchange Policy — Masala Valley',
                'meta_description' => 'Easy and convenient product exchange guidelines for Masala Valley customers.',
                'sort' => 11,
            ],
            [
                'title' => 'Cancellation Policy',
                'slug' => 'cancellation',
                'body' => '<h2>Order Cancellation Policy</h2>
<p>You can cancel your order at any time before it has been dispatched from our warehouse.</p>
<h3>How to Cancel</h3>
<ul>
    <li>Call our customer service hotline or message us on WhatsApp with your Order ID.</li>
    <li>Once dispatched, the order cannot be cancelled in transit, but you may refuse delivery or request an exchange upon arrival.</li>
</ul>',
                'meta_title' => 'Cancellation Policy — Masala Valley',
                'meta_description' => 'Cancellation rules and timelines for orders placed on Masala Valley.',
                'sort' => 12,
            ],
            [
                'title' => 'Pre-Order Policy',
                'slug' => 'pre-order',
                'body' => '<h2>Pre-Order Terms & Guidelines</h2>
<p>For seasonal organic items (such as raw Sundarbans honey, fresh seasonal dates, or specialized harvest spices), we offer pre-ordering so you are guaranteed fresh stock as soon as harvest arrives.</p>
<h3>Pre-Order Details</h3>
<ul>
    <li>Estimated shipping dates are listed on the product page.</li>
    <li>We will notify you via SMS/phone before dispatch.</li>
    <li>You may cancel your pre-order at any time prior to shipment.</li>
</ul>',
                'meta_title' => 'Pre-Order Policy — Masala Valley',
                'meta_description' => 'How pre-orders work for seasonal and limited-harvest items at Masala Valley.',
                'sort' => 13,
            ],
            [
                'title' => 'Extra Discount & Offers',
                'slug' => 'extra-discount',
                'body' => '<h2>Extra Discounts & Special Offers</h2>
<p>Save more when you shop smart at Masala Valley! We offer ongoing discounts and rewarding benefits for our valued shoppers.</p>
<h3>Ways to Get Extra Discounts</h3>
<ul>
    <li><strong>Combo Bundles:</strong> Save up to 15% - 25% when buying pre-packaged grocery and spice combos.</li>
    <li><strong>Coupon Codes:</strong> Apply active voucher codes at checkout for instant price cuts.</li>
    <li><strong>Free Shipping:</strong> Automatically get free home delivery inside Dhaka on orders over ৳1,500.</li>
    <li><strong>Free Gift Reward:</strong> Automatically unlock a special gift on orders over ৳3,000.</li>
</ul>',
                'meta_title' => 'Extra Discounts & Promotions — Masala Valley',
                'meta_description' => 'Learn how to unlock extra discounts, coupons, combos, and free gifts at Masala Valley.',
                'sort' => 14,
            ],
        ];

        foreach ($pages as $p) {
            Page::firstOrCreate(
                ['slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'body' => $p['body'],
                    'meta_title' => $p['meta_title'],
                    'meta_description' => $p['meta_description'],
                    'is_published' => true,
                    'sort' => $p['sort'],
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
