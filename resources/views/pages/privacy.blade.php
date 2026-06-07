@extends('layouts.app')
@section('title', 'Privacy Policy — Shuvo')

@section('content')

{{-- PAGE HEAD --}}
<div class="page-head">
  <div class="wrap">
    <div class="crumbs">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
      <span>Privacy Policy</span>
    </div>
    <h1>Privacy Policy</h1>
    <p class="sub">Last updated June 2026</p>
  </div>
</div>

{{-- CONTENT --}}
<div class="section">
  <div class="wrap">
    <div style="max-width:760px; margin:0 auto; font-size:15.5px; line-height:1.75; color:var(--ink-soft);">

      <p style="margin-bottom:28px;">
        Shuvo ("we", "our" or "the Company") operates the website shuvo.com and the Shuvo mobile application.
        This Privacy Policy explains how we collect, use, disclose and protect your personal information when you
        visit our platform or place an order with us. By using our services you agree to the practices described
        in this policy. If you do not agree, please discontinue using our platform.
      </p>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        1. Information We Collect
      </h3>
      <p style="margin-bottom:14px;">We collect information you provide directly to us, including:</p>
      <ul style="padding-left:20px; margin-bottom:14px; display:flex; flex-direction:column; gap:8px;">
        <li><strong>Account information:</strong> name, email address, phone number and delivery address when you register or place an order.</li>
        <li><strong>Payment information:</strong> bKash, Nagad or card transaction references. We do not store full card numbers; payments are processed by our payment gateway partners.</li>
        <li><strong>Communications:</strong> messages you send us via email, contact form or chat.</li>
      </ul>
      <p style="margin-bottom:14px;">We also collect information automatically when you use our platform:</p>
      <ul style="padding-left:20px; margin-bottom:14px; display:flex; flex-direction:column; gap:8px;">
        <li><strong>Device &amp; log data:</strong> IP address, browser type, operating system, pages visited and time spent.</li>
        <li><strong>Cookies:</strong> small text files stored on your device (see Section 3 below).</li>
        <li><strong>Order history:</strong> products purchased, quantities, delivery preferences.</li>
      </ul>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        2. How We Use Your Information
      </h3>
      <p style="margin-bottom:14px;">We use the information we collect to:</p>
      <ul style="padding-left:20px; margin-bottom:14px; display:flex; flex-direction:column; gap:8px;">
        <li>Process and fulfil your orders, including delivery coordination and order confirmation.</li>
        <li>Send you transactional communications (order receipts, shipping updates, delivery notifications).</li>
        <li>Respond to your enquiries and provide customer support.</li>
        <li>Send you marketing communications about new products, promotions and seasonal offers — only if you have opted in. You may opt out at any time.</li>
        <li>Improve our website, detect fraud and ensure security.</li>
        <li>Comply with applicable laws and regulations of Bangladesh.</li>
      </ul>
      <p>We will never sell or rent your personal information to third parties.</p>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        3. Cookies
      </h3>
      <p style="margin-bottom:14px;">
        We use cookies and similar technologies to operate our website, remember your cart, and understand how
        visitors use our platform. Cookies are small files stored in your browser. We use:
      </p>
      <ul style="padding-left:20px; margin-bottom:14px; display:flex; flex-direction:column; gap:8px;">
        <li><strong>Essential cookies:</strong> required for checkout, login sessions and cart functionality. These cannot be disabled.</li>
        <li><strong>Analytics cookies:</strong> help us understand traffic and usage patterns (e.g. page views). No personally identifiable information is transmitted.</li>
        <li><strong>Preference cookies:</strong> remember your display settings and language preference.</li>
      </ul>
      <p>You can control cookies through your browser settings. Disabling non-essential cookies will not affect your ability to shop.</p>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        4. Data Security
      </h3>
      <p style="margin-bottom:14px;">
        We implement industry-standard security measures including TLS/SSL encryption for data in transit,
        encrypted storage for sensitive fields and access controls limiting which staff can view personal data.
        Our servers are hosted in secure data centres with regular backups and intrusion detection.
      </p>
      <p>
        No transmission over the internet is completely secure. While we take every reasonable precaution,
        we cannot guarantee absolute security. If you believe your account has been compromised, please
        contact us immediately at <a href="mailto:hello@shuvo.com" style="color:var(--green);">hello@shuvo.com</a>.
      </p>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        5. Third-Party Services
      </h3>
      <p style="margin-bottom:14px;">We share data with trusted third parties only to the extent necessary to operate our business:</p>
      <ul style="padding-left:20px; margin-bottom:14px; display:flex; flex-direction:column; gap:8px;">
        <li><strong>Delivery partners</strong> (e.g. Pathao, Steadfast, Sundarban Courier): your name, address and phone number to complete delivery.</li>
        <li><strong>Payment processors</strong> (bKash, Nagad, SSLCommerz): transaction data required by the processor to complete payment.</li>
        <li><strong>Analytics tools:</strong> aggregated, anonymised data only. No personally identifiable information.</li>
        <li><strong>SMS/email providers:</strong> for sending order confirmations and delivery updates.</li>
      </ul>
      <p>All third parties are contractually bound to handle your data securely and only for the stated purpose.</p>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        6. Your Rights
      </h3>
      <p style="margin-bottom:14px;">You have the right to:</p>
      <ul style="padding-left:20px; margin-bottom:14px; display:flex; flex-direction:column; gap:8px;">
        <li><strong>Access</strong> the personal information we hold about you.</li>
        <li><strong>Correct</strong> inaccurate or incomplete information.</li>
        <li><strong>Delete</strong> your account and associated personal data (subject to legal retention obligations).</li>
        <li><strong>Withdraw consent</strong> to marketing communications at any time.</li>
        <li><strong>Data portability:</strong> request a copy of your data in a structured, machine-readable format.</li>
      </ul>
      <p>To exercise any of these rights, email us at <a href="mailto:hello@shuvo.com" style="color:var(--green);">hello@shuvo.com</a> with your request. We will respond within 14 business days.</p>

      <h3 style="font-size:19px; color:var(--ink); margin:36px 0 12px; letter-spacing:-.01em;">
        7. Contact
      </h3>
      <p style="margin-bottom:14px;">
        If you have any questions, concerns or complaints about this Privacy Policy or how we handle your data,
        please contact our Data Protection Officer:
      </p>
      <address style="not-italic:none; font-style:normal; background:var(--surface-2); border:1px solid var(--line); border-radius:var(--radius); padding:20px 24px; margin-bottom:14px; line-height:1.8;">
        <strong>Shuvo Organic Grocery</strong><br>
        House 14, Road 5, Block B, Rampura, Dhaka 1219, Bangladesh<br>
        Email: <a href="mailto:hello@shuvo.com" style="color:var(--green);">hello@shuvo.com</a><br>
        Phone: 09642-XXXXXX
      </address>
      <p>We aim to resolve all privacy-related enquiries promptly and fairly.</p>

    </div>
  </div>
</div>

@endsection
