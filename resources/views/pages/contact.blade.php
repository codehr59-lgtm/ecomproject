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
@endphp
@section('title', 'Contact — ' . $__siteName)

@section('content')

{{-- PAGE HEAD --}}
<div class="page-head">
  <div class="wrap">
    <div class="crumbs">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
      <span>Contact</span>
    </div>
    <h1>{{ $cTitle }}</h1>
    @if($cSubtitle)
    <p class="sub">{{ $cSubtitle }}</p>
    @endif
  </div>
</div>

<style>
  .contact-grid {
    display: grid;
    grid-template-columns: 1.25fr 1fr;
    gap: 32px;
    align-items: start;
    width: 100%;
  }
  .contact-info-list {
    display: flex;
    flex-direction: column;
    gap: 18px;
  }
  .contact-info-item {
    display: flex;
    gap: 14px;
    align-items: flex-start;
  }
  .contact-map-wrapper {
    border-radius: var(--radius, 14px);
    overflow: hidden;
    border: 1px solid var(--line, #e2e8f0);
    box-shadow: var(--shadow-s, 0 1px 3px rgba(0,0,0,0.06));
    background: #f8fafc;
  }
  .contact-map-wrapper iframe {
    width: 100%;
    height: 220px;
    border: 0;
    display: block;
  }

  @media (max-width: 860px) {
    .contact-grid {
      grid-template-columns: 1fr !important;
      gap: 22px !important;
    }
    .contact-grid .field-row {
      grid-template-columns: 1fr !important;
      gap: 12px !important;
    }
    .contact-grid .co-card {
      padding: 20px 16px !important;
    }
  }
</style>

{{-- CONTACT BODY --}}
<div class="section">
  <div class="wrap">
    <div class="contact-grid">

      {{-- LEFT — CONTACT FORM --}}
      <div class="co-card" style="margin:0;">
        <h3>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="color:var(--green);">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
          {{ $cFormTitle }}
        </h3>

        @if(session('success'))
        <div style="padding:12px 16px;background:var(--green-soft);color:var(--green-deep);border-radius:var(--radius);margin-bottom:16px;font-size:14px;font-weight:600;">
          {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div style="padding:12px 16px;background:#fef2f2;color:#dc2626;border-radius:var(--radius);margin-bottom:16px;font-size:14px;">
          @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
        @endif

        <form action="{{ route('contact.store') }}" method="post">
          @csrf
          <div class="field-row">
            <div class="field">
              <label for="cf-name">Your Name <span style="color:#ef4444;">*</span></label>
              <input type="text" id="cf-name" name="name" placeholder="e.g. Rahim Ahmed" autocomplete="name" required>
            </div>
            <div class="field">
              <label for="cf-phone">Phone Number</label>
              <input type="tel" id="cf-phone" name="phone" placeholder="+880 1XXX-XXXXXX" autocomplete="tel">
            </div>
          </div>
          <div class="field">
            <label for="cf-email">Email Address</label>
            <input type="email" id="cf-email" name="email" placeholder="you@example.com" autocomplete="email">
          </div>
          <div class="field">
            <label for="cf-subject">Subject</label>
            <input type="text" id="cf-subject" name="subject" placeholder="Order inquiry, product question, feedback…">
          </div>
          <div class="field">
            <label for="cf-message">Your Message <span style="color:#ef4444;">*</span></label>
            <textarea id="cf-message" name="message" rows="5" placeholder="Write your message here…" style="resize:vertical;" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-block" style="margin-top:6px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="22" y1="2" x2="11" y2="13"/>
              <polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
            Send Message
          </button>
        </form>
      </div>

      {{-- RIGHT — CONTACT INFO --}}
      <div style="display:flex; flex-direction:column; gap:20px;">

        <div class="co-card" style="margin:0;">
          <h3>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="color:var(--green);">
              <circle cx="12" cy="12" r="10"/>
              <polyline points="12 6 12 12 16 14"/>
            </svg>
            Contact Details
          </h3>

          <div class="contact-info-list">

            @if($cAddress)
            <div class="contact-info-item">
              <div class="trust-ico" style="flex-shrink:0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                  <circle cx="12" cy="10" r="3"/>
                </svg>
              </div>
              <div>
                <b style="font-size:14px; display:block; margin-bottom:3px;">Address</b>
                <span style="font-size:14px; color:var(--ink-soft); line-height:1.5;">{!! nl2br(e($cAddress)) !!}</span>
              </div>
            </div>
            @endif

            @if($cPhone)
            <div class="contact-info-item">
              <div class="trust-ico" style="flex-shrink:0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8a19.79 19.79 0 01-3.07-8.64A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                </svg>
              </div>
              <div>
                <b style="font-size:14px; display:block; margin-bottom:3px;">Phone / WhatsApp</b>
                <a href="tel:{{ preg_replace('/[^\d+]/', '', $cPhone) }}" style="font-size:14px; color:var(--green); font-weight:600;">{{ $cPhone }}</a>
                @if($cPhoneTiming)
                <span style="font-size:13px; color:var(--muted); display:block;">{{ $cPhoneTiming }}</span>
                @endif
              </div>
            </div>
            @endif

            @if($cEmail)
            <div class="contact-info-item">
              <div class="trust-ico" style="flex-shrink:0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                  <polyline points="22,6 12,13 2,6"/>
                </svg>
              </div>
              <div>
                <b style="font-size:14px; display:block; margin-bottom:3px;">Email</b>
                <a href="mailto:{{ $cEmail }}" style="font-size:14px; color:var(--green); font-weight:600;">{{ $cEmail }}</a>
                @if($cEmailTiming)
                <span style="font-size:13px; color:var(--muted); display:block;">{{ $cEmailTiming }}</span>
                @endif
              </div>
            </div>
            @endif

            @if($cHours1 || $cHours2)
            <div class="contact-info-item">
              <div class="trust-ico" style="flex-shrink:0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                  <line x1="16" y1="2" x2="16" y2="6"/>
                  <line x1="8" y1="2" x2="8" y2="6"/>
                  <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
              </div>
              <div>
                <b style="font-size:14px; display:block; margin-bottom:3px;">Business Hours</b>
                @if($cHours1)<span style="font-size:14px; color:var(--ink-soft); display:block;">{{ $cHours1 }}</span>@endif
                @if($cHours2)<span style="font-size:14px; color:var(--ink-soft); display:block;">{{ $cHours2 }}</span>@endif
              </div>
            </div>
            @endif
          </div>

          {{-- Social links --}}
          @if($cShowSocial && ($fbUrl || $igUrl || $waUrl))
          <div style="margin-top:20px; padding-top:18px; border-top:1px solid var(--line);">
            <p style="font-size:13px; font-weight:600; color:var(--ink-soft); margin:0 0 10px;">Follow us</p>
            <div class="ftr-social">
              @if($fbUrl)
              <a href="{{ $fbUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook" style="background:var(--green-soft);">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
              </a>
              @endif
              @if($igUrl)
              <a href="{{ $igUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram" style="background:var(--green-soft);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                  <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/>
                  <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                </svg>
              </a>
              @endif
              @if($waUrl)
              <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp" style="background:var(--green-soft);">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.126.558 4.12 1.534 5.847L0 24l6.347-1.498A11.941 11.941 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.016-1.373l-.36-.214-3.727.879.937-3.629-.234-.373A9.818 9.818 0 0112 2.182c5.427 0 9.818 4.391 9.818 9.818 0 5.428-4.391 9.818-9.818 9.818z"/></svg>
              </a>
              @endif
            </div>
          </div>
          @endif
        </div>

        {{-- MAP DISPLAY --}}
        @if($cShowMap)
          @if($cMapIframe)
            <div class="contact-map-wrapper">
              {!! $cMapIframe !!}
            </div>
          @else
            <div class="ph" style="height:190px; border-radius:var(--radius); border:1px solid var(--line); --ph-bg:#C8D6B0;">
              <div class="ph-inner">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:36px;height:36px;color:var(--green);margin:0 auto 8px;">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                  <circle cx="12" cy="10" r="3"/>
                </svg>
                <div class="ph-label" style="font-weight:700; color:var(--green-deep);">MAP — {{ $cMapLabel }}</div>
              </div>
            </div>
          @endif
        @endif

      </div>
    </div>
  </div>
</div>

@endsection
