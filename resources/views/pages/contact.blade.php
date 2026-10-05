@extends('layouts.app')
@php
    $__siteName  = \App\Models\Setting::get('site_name', 'Masala Valley');
    $cTitle      = \App\Models\Setting::get('contact_page_title', 'Get in Touch');
    $cSubtitle   = \App\Models\Setting::get('contact_page_subtitle', "Questions about an order, a product, or just want to say hello? We'd love to hear from you.");
    $cFormTitle  = \App\Models\Setting::get('contact_page_form_title', 'Send us a message');
    $cAddress    = \App\Models\Setting::get('contact_address', 'House 14, Road 5, Block B, Rampura, Dhaka 1219, Bangladesh');
    $cPhone      = \App\Models\Setting::get('contact_phone', '09642-XXXXXX');
    $cPhoneTiming= \App\Models\Setting::get('contact_phone_timing', 'Sat–Thu 9 am – 9 pm');
    $cEmail      = \App\Models\Setting::get('contact_email', 'hello@masalavalley.com');
    $cEmailTiming= \App\Models\Setting::get('contact_email_timing', 'We reply within 6 hours');
    $cHours1     = \App\Models\Setting::get('contact_hours_1', 'Sat – Thu: 9:00 am – 9:00 pm');
    $cHours2     = \App\Models\Setting::get('contact_hours_2', 'Friday: 2:00 pm – 8:00 pm');
    $cMapLabel   = \App\Models\Setting::get('contact_map_label', 'Rampura, Dhaka');
    $cMapIframe  = \App\Models\Setting::get('contact_map_iframe');
    $cShowMap    = (bool) \App\Models\Setting::get('contact_show_map', true);
    $cShowSocial = (bool) \App\Models\Setting::get('contact_show_social', true);

    $fbUrl       = \App\Models\Setting::get('social_facebook', '');
    $igUrl       = \App\Models\Setting::get('social_instagram', '');
    $waNum       = \App\Models\Setting::get('social_whatsapp') ?: \App\Models\Setting::get('floating_whatsapp_number', '');
    $waClean     = preg_replace('/[^0-9]/', '', (string)$waNum);
    if (strlen($waClean) == 11 && str_starts_with($waClean, '01')) {
        $waClean = '88' . $waClean;
    }
    $waUrl       = $waClean ? ('https://wa.me/' . $waClean) : '';
    $mapSearchUrl= 'https://www.google.com/maps/search/?api=1&query=' . urlencode($cAddress ?: $cMapLabel);
@endphp

@section('title', 'Contact Us — ' . $__siteName)

@section('content')

<style>
  .contact-section {
    padding: 30px 0 60px;
  }
  .contact-header {
    text-align: center;
    max-width: 680px;
    margin: 0 auto 36px;
  }
  .contact-header .badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--green-tint, #e8f5e9);
    color: var(--green, #2e7d32);
    font-size: 12.5px;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 999px;
    margin-bottom: 12px;
    letter-spacing: .02em;
  }
  .contact-header h1 {
    font-size: clamp(26px, 3.2vw, 38px);
    font-weight: 800;
    color: var(--ink, #1e293b);
    margin: 0 0 10px;
    letter-spacing: -.02em;
  }
  .contact-header p {
    font-size: 15px;
    color: var(--ink-soft, #64748b);
    line-height: 1.6;
    margin: 0;
  }

  .contact-layout {
    display: grid;
    grid-template-columns: 1.25fr 1fr;
    gap: 32px;
    align-items: start;
  }

  /* Cards */
  .cnt-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid var(--line, #e2e8f0);
    box-shadow: 0 4px 20px -2px rgba(0,0,0,0.05);
    padding: 32px;
    transition: box-shadow .2s ease;
  }
  .cnt-card:hover {
    box-shadow: 0 8px 30px -4px rgba(0,0,0,0.08);
  }

  /* Card Head */
  .cnt-card-head {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 24px;
    padding-bottom: 18px;
    border-bottom: 1px solid var(--line, #e2e8f0);
  }
  .cnt-icon-box {
    width: 44px;
    height: 44px;
    min-width: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .cnt-icon-box svg {
    width: 22px;
    height: 22px;
  }
  .cnt-icon-green {
    background: rgba(46,125,50,0.1);
    color: var(--green, #2e7d32);
  }
  .cnt-icon-honey {
    background: rgba(194,135,42,0.12);
    color: var(--honey, #c2872a);
  }
  .cnt-card-head h2 {
    font-size: 20px;
    font-weight: 700;
    color: var(--ink, #1e293b);
    margin: 0;
    line-height: 1.2;
  }
  .cnt-card-head p {
    font-size: 12.5px;
    color: var(--muted, #94a3b8);
    margin: 3px 0 0;
  }

  /* Form Elements */
  .cnt-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
  }
  .cnt-form-group {
    margin-bottom: 16px;
  }
  .cnt-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--ink, #1e293b);
    margin-bottom: 6px;
  }
  .cnt-label .req {
    color: #ef4444;
  }
  .cnt-input,
  .cnt-textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid var(--line, #e2e8f0);
    border-radius: 10px;
    font-size: 14px;
    color: var(--ink, #1e293b);
    background: #ffffff;
    transition: all .15s ease;
    box-sizing: border-box;
    font-family: inherit;
  }
  .cnt-input:focus,
  .cnt-textarea:focus {
    outline: none;
    border-color: var(--green, #2e7d32);
    box-shadow: 0 0 0 3px rgba(46,125,50,0.12);
  }
  .cnt-input::placeholder,
  .cnt-textarea::placeholder {
    color: #94a3b8;
  }

  /* Submit Button */
  .cnt-btn-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 13px 24px;
    background: var(--green, #2e7d32);
    color: #ffffff;
    font-size: 15px;
    font-weight: 700;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(46,125,50,0.25);
    transition: all .18s ease;
    margin-top: 6px;
  }
  .cnt-btn-submit:hover {
    background: var(--green-deep, #1b4332);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(46,125,50,0.32);
  }
  .cnt-btn-submit svg {
    width: 18px;
    height: 18px;
    transition: transform .15s ease;
  }
  .cnt-btn-submit:hover svg {
    transform: translateX(3px);
  }

  /* Right Side Info Items */
  .cnt-info-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  .cnt-info-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
  }
  .cnt-info-ico {
    width: 40px;
    height: 40px;
    min-width: 40px;
    border-radius: 10px;
    background: var(--surface-2, #f8fafc);
    border: 1px solid var(--line, #e2e8f0);
    color: var(--green, #2e7d32);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .cnt-info-ico svg {
    width: 19px;
    height: 19px;
  }
  .cnt-info-content {
    flex: 1;
    min-width: 0;
  }
  .cnt-info-content b {
    display: block;
    font-size: 13.5px;
    font-weight: 700;
    color: var(--ink, #1e293b);
    margin-bottom: 2px;
  }
  .cnt-info-content p,
  .cnt-info-content a {
    font-size: 14px;
    color: var(--ink-soft, #475569);
    line-height: 1.5;
    margin: 0;
    word-break: break-word;
  }
  .cnt-info-content a {
    color: var(--green, #2e7d32);
    font-weight: 600;
    text-decoration: none;
    transition: color .15s;
  }
  .cnt-info-content a:hover {
    color: var(--green-deep, #1b4332);
    text-decoration: underline;
  }
  .cnt-pill {
    display: inline-block;
    font-size: 11.5px;
    font-weight: 600;
    color: #64748b;
    background: #f1f5f9;
    padding: 2px 8px;
    border-radius: 6px;
    margin-top: 4px;
  }

  /* Social links strip */
  .cnt-social-strip {
    margin-top: 22px;
    padding-top: 18px;
    border-top: 1px solid var(--line, #e2e8f0);
  }
  .cnt-social-strip span {
    display: block;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #94a3b8;
    margin-bottom: 10px;
  }
  .cnt-social-btns {
    display: flex;
    gap: 10px;
  }
  .cnt-social-btn {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: var(--surface-2, #f8fafc);
    border: 1px solid var(--line, #e2e8f0);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ink-soft, #475569);
    text-decoration: none;
    transition: all .15s ease;
  }
  .cnt-social-btn:hover {
    background: var(--green, #2e7d32);
    color: #ffffff;
    border-color: var(--green, #2e7d32);
    transform: translateY(-2px);
  }
  .cnt-social-btn svg {
    width: 18px;
    height: 18px;
  }

  /* Map card */
  .cnt-map-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid var(--line, #e2e8f0);
    box-shadow: 0 4px 20px -2px rgba(0,0,0,0.05);
    overflow: hidden;
    margin-top: 24px;
  }
  .cnt-map-inner {
    padding: 24px;
    background: linear-gradient(135deg, #f8faf8 0%, #f1f5f1 100%);
    text-align: center;
  }
  .cnt-map-badge {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    background: #ffffff;
    color: var(--green, #2e7d32);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    margin-bottom: 12px;
  }
  .cnt-map-badge svg {
    width: 26px;
    height: 26px;
  }
  .cnt-map-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    color: var(--green, #2e7d32);
    font-size: 13px;
    font-weight: 700;
    padding: 9px 18px;
    border-radius: 999px;
    border: 1px solid var(--line, #e2e8f0);
    text-decoration: none;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    transition: all .15s ease;
    margin-top: 14px;
  }
  .cnt-map-btn:hover {
    background: var(--green, #2e7d32);
    color: #ffffff;
    border-color: var(--green, #2e7d32);
    transform: translateY(-1px);
  }
  .cnt-map-iframe-box {
    width: 100%;
    height: 260px;
    border: none;
    display: block;
  }
  .cnt-map-iframe-box iframe {
    width: 100%;
    height: 100%;
    border: none;
    display: block;
  }

  /* Responsive styles */
  @media (max-width: 860px) {
    .contact-layout {
      grid-template-columns: 1fr;
      gap: 24px;
    }
    .cnt-card {
      padding: 22px 18px;
      border-radius: 16px;
    }
    .cnt-form-row {
      grid-template-columns: 1fr;
      gap: 0;
    }
    .contact-header {
      margin-bottom: 24px;
      text-align: left;
    }
  }
</style>

<div class="contact-section">
  <div class="wrap">

    {{-- Breadcrumbs --}}
    <div class="crumbs" style="margin-bottom:20px;">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
      <span>Contact Us</span>
    </div>

    {{-- Section Header --}}
    <div class="contact-header">
      <span class="badge">
        <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        We're here to help you
      </span>
      <h1>{{ $cTitle }}</h1>
      @if($cSubtitle)
      <p>{{ $cSubtitle }}</p>
      @endif
    </div>

    {{-- Main Layout --}}
    <div class="contact-layout">

      {{-- Left Column: Contact Form --}}
      <div class="cnt-card">
        <div class="cnt-card-head">
          <div class="cnt-icon-box cnt-icon-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="20" height="16" x="2" y="4" rx="2"></rect>
              <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
            </svg>
          </div>
          <div>
            <h2>{{ $cFormTitle }}</h2>
            <p>Fill out the form and our support team will reply promptly.</p>
          </div>
        </div>

        @if(session('success'))
        <div style="padding:14px 18px;background:rgba(46,125,50,0.1);color:var(--green, #2e7d32);border-radius:12px;margin-bottom:20px;font-size:14px;font-weight:600;display:flex;align-items:center;gap:10px;border:1px solid rgba(46,125,50,0.2);">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
          <span>{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div style="padding:14px 18px;background:#fef2f2;color:#dc2626;border-radius:12px;margin-bottom:20px;font-size:14px;border:1px solid #fecaca;">
          <b style="display:block;margin-bottom:4px;">Please fix the following:</b>
          <ul style="margin:0;padding-left:18px;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <form action="{{ route('contact.store') }}" method="post">
          @csrf
          <div class="cnt-form-row">
            <div class="cnt-form-group">
              <label for="cf-name" class="cnt-label">Your Full Name <span class="req">*</span></label>
              <input type="text" id="cf-name" name="name" class="cnt-input" placeholder="e.g. Rahim Ahmed" autocomplete="name" required value="{{ old('name') }}">
            </div>
            <div class="cnt-form-group">
              <label for="cf-phone" class="cnt-label">Phone Number</label>
              <input type="tel" id="cf-phone" name="phone" class="cnt-input" placeholder="+880 1XXX-XXXXXX" autocomplete="tel" value="{{ old('phone') }}">
            </div>
          </div>

          <div class="cnt-form-row">
            <div class="cnt-form-group">
              <label for="cf-email" class="cnt-label">Email Address</label>
              <input type="email" id="cf-email" name="email" class="cnt-input" placeholder="you@example.com" autocomplete="email" value="{{ old('email') }}">
            </div>
            <div class="cnt-form-group">
              <label for="cf-subject" class="cnt-label">Subject</label>
              <input type="text" id="cf-subject" name="subject" class="cnt-input" placeholder="Order inquiry, question…" value="{{ old('subject') }}">
            </div>
          </div>

          <div class="cnt-form-group">
            <label for="cf-message" class="cnt-label">Your Message <span class="req">*</span></label>
            <textarea id="cf-message" name="message" class="cnt-textarea" rows="5" placeholder="Write your message here…" required style="resize:vertical;">{{ old('message') }}</textarea>
          </div>

          <button type="submit" class="cnt-btn-submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="22" y1="2" x2="11" y2="13"></line>
              <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
            Send Message
          </button>
        </form>
      </div>

      {{-- Right Column: Contact Info & Location --}}
      <div>

        {{-- Details Card --}}
        <div class="cnt-card">
          <div class="cnt-card-head">
            <div class="cnt-icon-box cnt-icon-honey">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
            </div>
            <div>
              <h2>Contact Details</h2>
              <p>Direct lines of communication with our team.</p>
            </div>
          </div>

          <div class="cnt-info-list">

            {{-- Address --}}
            @if($cAddress)
            <div class="cnt-info-item">
              <div class="cnt-info-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
              </div>
              <div class="cnt-info-content">
                <b>Store & Office Address</b>
                <p>{!! nl2br(e($cAddress)) !!}</p>
              </div>
            </div>
            @endif

            {{-- Phone / WhatsApp --}}
            @if($cPhone)
            <div class="cnt-info-item">
              <div class="cnt-info-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 3.07 9.8 19.79 19.79 0 0 1 0 1.16A2 2 0 0 1 2.18 0h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L6.09 7.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
              </div>
              <div class="cnt-info-content">
                <b>Phone / WhatsApp</b>
                <a href="tel:{{ preg_replace('/[^\d+]/', '', $cPhone) }}">{{ $cPhone }}</a>
                @if($cPhoneTiming)
                <span class="cnt-pill">🕒 {{ $cPhoneTiming }}</span>
                @endif
              </div>
            </div>
            @endif

            {{-- Email --}}
            @if($cEmail)
            <div class="cnt-info-item">
              <div class="cnt-info-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                  <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                </svg>
              </div>
              <div class="cnt-info-content">
                <b>Email Support</b>
                <a href="mailto:{{ $cEmail }}">{{ $cEmail }}</a>
                @if($cEmailTiming)
                <span class="cnt-pill">⚡ {{ $cEmailTiming }}</span>
                @endif
              </div>
            </div>
            @endif

            {{-- Business Hours --}}
            @if($cHours1 || $cHours2)
            <div class="cnt-info-item">
              <div class="cnt-info-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
              </div>
              <div class="cnt-info-content">
                <b>Business Hours</b>
                @if($cHours1)<p>{{ $cHours1 }}</p>@endif
                @if($cHours2)<p style="color:#64748b;font-size:13.5px;">{{ $cHours2 }}</p>@endif
              </div>
            </div>
            @endif

          </div>

          {{-- Social Media Links --}}
          @if($cShowSocial && ($fbUrl || $igUrl || $waUrl))
          <div class="cnt-social-strip">
            <span>Connect On Social Media</span>
            <div class="cnt-social-btns">
              @if($fbUrl)
              <a href="{{ $fbUrl }}" target="_blank" rel="noopener noreferrer" class="cnt-social-btn" title="Facebook" aria-label="Facebook">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
              </a>
              @endif
              @if($igUrl)
              <a href="{{ $igUrl }}" target="_blank" rel="noopener noreferrer" class="cnt-social-btn" title="Instagram" aria-label="Instagram">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                  <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                  <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                </svg>
              </a>
              @endif
              @if($waUrl)
              <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="cnt-social-btn" title="WhatsApp" aria-label="WhatsApp">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.126.558 4.12 1.534 5.847L0 24l6.347-1.498A11.941 11.941 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 0 1-5.016-1.373l-.36-.214-3.727.879.937-3.629-.234-.373A9.818 9.818 0 0 1 12 2.182c5.427 0 9.818 4.391 9.818 9.818 0 5.428-4.391 9.818-9.818 9.818z"/></svg>
              </a>
              @endif
            </div>
          </div>
          @endif
        </div>

        {{-- Map / Location Card --}}
        @if($cShowMap)
        <div class="cnt-map-card">
          @if($cMapIframe)
            <div class="cnt-map-iframe-box">
              {!! $cMapIframe !!}
            </div>
          @else
            <div class="cnt-map-inner">
              <div class="cnt-map-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
              </div>
              <h3 style="font-size:17px;font-weight:700;color:var(--ink, #1e293b);margin:0 0 6px;">{{ $cMapLabel }}</h3>
              <p style="font-size:13.5px;color:var(--ink-soft, #64748b);margin:0;max-width:320px;margin:0 auto;">
                {{ $cAddress ?: 'Visit our store and distribution center in Rampura, Dhaka.' }}
              </p>
              <a href="{{ $mapSearchUrl }}" target="_blank" rel="noopener noreferrer" class="cnt-map-btn">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" x2="21" y1="14" y2="3"></line></svg>
                Open in Google Maps
              </a>
            </div>
          @endif
        </div>
        @endif

      </div>

    </div>

  </div>
</div>

@endsection
