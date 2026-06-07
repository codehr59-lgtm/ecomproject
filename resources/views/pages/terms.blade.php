@extends('layouts.app')
@section('title', 'Terms & Conditions — Shuvo')

@section('content')

{{-- PAGE HEAD --}}
<div class="page-head">
  <div class="wrap">
    <div class="crumbs">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
      <span>Terms &amp; Conditions</span>
    </div>
    <h1>Terms &amp; Conditions</h1>
    <p class="sub">Last updated June 2026</p>
  </div>
</div>

{{-- CONTENT --}}
<div class="section">
  <div class="wrap">
    <div style="max-width:760px; margin:0 auto; font-size:15.5px; line-height:1.75; color:var(--ink-soft);">

      <p style="margin-bottom:28px;">
        These Terms and Conditions ("Terms") govern your use of the Shuvo website (shuvo.com) and all related
        services operated by Shuvo Organic Grocery ("Shuvo", "we", "us" or "our"), a business registered in
        Dhaka, Bangladesh. By placing an order or using our platform, you confirm that you have read,
        understood and agree to be bound by these Terms.
      </p>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        1. Acceptance of Terms
      </h3>
      <p>
        By accessing or using shuvo.com, creating an account or placing an order, you accept these Terms in full.
        If you do not agree with any part of these Terms, you must not use our platform. We reserve the right
        to update these Terms at any time. Continued use of the platform after changes constitutes your
        acceptance of the revised Terms.
      </p>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        2. Orders &amp; Pricing
      </h3>
      <p style="margin-bottom:14px;">
        All orders placed on shuvo.com are subject to product availability and our confirmation. Placing an order
        constitutes an offer to purchase — a binding contract is formed only when we send your order confirmation
        via SMS or email.
      </p>
      <ul style="padding-left:20px; margin-bottom:14px; display:flex; flex-direction:column; gap:8px;">
        <li>Prices are displayed in Bangladeshi Taka (BDT) and include VAT where applicable.</li>
        <li>We reserve the right to change prices at any time. The price you pay is the price shown at the time you confirm your order.</li>
        <li>Promotional prices and discount codes are valid for the period stated and cannot be applied retrospectively.</li>
        <li>We reserve the right to cancel any order that appears to be placed fraudulently or in violation of these Terms, and to refund the amount paid in full.</li>
      </ul>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        3. Payment
      </h3>
      <p style="margin-bottom:14px;">We accept the following payment methods:</p>
      <ul style="padding-left:20px; margin-bottom:14px; display:flex; flex-direction:column; gap:8px;">
        <li><strong>Cash on Delivery (COD):</strong> available across Bangladesh. Payment is made to the courier upon receipt of your order.</li>
        <li><strong>bKash / Nagad:</strong> mobile financial services, processed at checkout.</li>
        <li><strong>Card Payment:</strong> Visa, Mastercard and other major cards via our secure payment gateway (SSLCommerz).</li>
      </ul>
      <p>
        For COD orders, if the customer is unavailable for two consecutive delivery attempts, the order may be
        cancelled and the item returned to our warehouse. A re-delivery charge may apply for a third attempt.
      </p>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        4. Delivery
      </h3>
      <p style="margin-bottom:14px;">
        We deliver to all 64 districts of Bangladesh. Estimated delivery times:
      </p>
      <ul style="padding-left:20px; margin-bottom:14px; display:flex; flex-direction:column; gap:8px;">
        <li><strong>Dhaka city (same-day):</strong> orders placed before 1:00 pm on business days, delivered by 8:00 pm.</li>
        <li><strong>Dhaka city (standard):</strong> next-day delivery for orders placed after 1:00 pm.</li>
        <li><strong>Outside Dhaka:</strong> 2–4 business days via courier partner (Sundarban, Steadfast or Pathao).</li>
      </ul>
      <p style="margin-bottom:14px;">
        Delivery charges are shown at checkout and vary by location. Orders above ৳1,500 qualify for free
        delivery within Dhaka. We are not responsible for delays caused by courier partners, natural disasters,
        hartals, strikes or other circumstances beyond our control.
      </p>
      <p>
        Perishable products (fresh fruit, certain dairy) must be inspected at delivery. Claims regarding
        damaged or missing perishable items must be made within 2 hours of delivery with photographic evidence.
      </p>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        5. Returns &amp; Refunds
      </h3>
      <p style="margin-bottom:14px;">
        We stand behind the quality of every product. If you receive an item that is damaged, incorrect or
        does not match its description, we will provide a full refund or replacement at our discretion.
      </p>
      <ul style="padding-left:20px; margin-bottom:14px; display:flex; flex-direction:column; gap:8px;">
        <li>Claims must be raised within <strong>48 hours</strong> of delivery for non-perishable items.</li>
        <li>Claims for perishable items (fresh produce, dairy) must be raised within <strong>2 hours</strong> with photos.</li>
        <li>Opened, consumed or partially used products are not eligible for return unless found to be defective or adulterated.</li>
        <li>Refunds are processed to the original payment method within 5–7 business days of claim approval.</li>
        <li>For COD orders, refunds are issued via bKash or bank transfer. We will confirm your preferred method.</li>
      </ul>
      <p>To raise a return or refund claim, contact us at <a href="mailto:hello@shuvo.com" style="color:var(--green);">hello@shuvo.com</a> or call 09642-XXXXXX.</p>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        6. Pre-Orders
      </h3>
      <p style="margin-bottom:14px;">
        Certain seasonal products — notably fresh mangoes (Himsagar, Langra, Haribhanga), hilsa fish and
        specific harvest-season items — are offered as pre-orders. By placing a pre-order you agree to:
      </p>
      <ul style="padding-left:20px; margin-bottom:14px; display:flex; flex-direction:column; gap:8px;">
        <li>Pay a deposit (or full amount) at the time of ordering, as indicated on the product page.</li>
        <li>Accept that delivery will occur during the stated harvest window (typically communicated via SMS/email 1 week in advance).</li>
        <li>Acknowledge that pre-order items are subject to availability from our farm partners and that natural factors (weather, yield) may affect final quantity.</li>
        <li>In the event we cannot fulfil a pre-order, a full refund will be issued within 5 business days.</li>
      </ul>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        7. Limitation of Liability
      </h3>
      <p style="margin-bottom:14px;">
        To the fullest extent permitted by Bangladesh law:
      </p>
      <ul style="padding-left:20px; margin-bottom:14px; display:flex; flex-direction:column; gap:8px;">
        <li>Shuvo is not liable for any indirect, incidental or consequential damages arising from the use of our platform or products.</li>
        <li>Our total liability for any claim shall not exceed the value of the specific order giving rise to that claim.</li>
        <li>We do not warrant that our website will be uninterrupted or error-free at all times.</li>
        <li>Product descriptions, weights and nutritional information are as accurate as possible but may vary slightly due to the natural characteristics of organic produce.</li>
      </ul>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        8. Governing Law
      </h3>
      <p>
        These Terms are governed by and construed in accordance with the laws of the People's Republic of
        Bangladesh. Any disputes arising under or in connection with these Terms shall be subject to the
        exclusive jurisdiction of the courts of Dhaka, Bangladesh. We will always attempt to resolve disputes
        informally and in good faith before resorting to formal proceedings.
      </p>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        9. Contact
      </h3>
      <p style="margin-bottom:14px;">
        If you have any questions about these Terms, please contact us:
      </p>
      <address style="not-italic:none; font-style:normal; background:var(--surface-2); border:1px solid var(--line); border-radius:var(--radius); padding:20px 24px; margin-bottom:14px; line-height:1.8;">
        <strong>Shuvo Organic Grocery</strong><br>
        House 14, Road 5, Block B, Rampura, Dhaka 1219, Bangladesh<br>
        Email: <a href="mailto:hello@shuvo.com" style="color:var(--green);">hello@shuvo.com</a><br>
        Phone: 09642-XXXXXX<br>
        Business hours: Sat–Thu 9:00 am – 9:00 pm
      </address>

    </div>
  </div>
</div>

@endsection
