@php
    $__fcEnabled     = (bool) \App\Models\Setting::get('floating_contact_enabled', true);
    if (! $__fcEnabled) {
        return;
    }

    $__fcPosition    = \App\Models\Setting::get('floating_contact_position', 'right'); // 'right' or 'left'
    $__fcBtnBg       = \App\Models\Setting::get('floating_contact_btn_bg', '#9B5123');
    $__fcBtnIconColor= \App\Models\Setting::get('floating_contact_btn_icon_color', '#ffffff');
    $__fcBadge       = (bool) \App\Models\Setting::get('floating_contact_badge', true);
    $__fcTooltip     = \App\Models\Setting::get('floating_contact_tooltip', 'Need help? Contact us');

    // WhatsApp
    $__fcWaEnabled   = (bool) \App\Models\Setting::get('floating_whatsapp_enabled', true);
    $__fcWaNum       = \App\Models\Setting::get('floating_whatsapp_number') ?: \App\Models\Setting::get('social_whatsapp') ?: \App\Models\Setting::get('contact_phone', '01700000000');
    $__fcWaClean     = preg_replace('/[^0-9]/', '', (string) $__fcWaNum);
    if (strlen($__fcWaClean) == 11 && str_starts_with($__fcWaClean, '01')) {
        $__fcWaClean = '88' . $__fcWaClean;
    }
    $__fcWaMsg       = \App\Models\Setting::get('floating_whatsapp_message', 'Hello Masala Valley, I want to inquire about a product.');
    $__fcWaUrl       = 'https://wa.me/' . $__fcWaClean . ($__fcWaMsg ? '?text=' . urlencode($__fcWaMsg) : '');
    $__fcWaLabel     = \App\Models\Setting::get('floating_whatsapp_label', 'WhatsApp');

    // Messenger
    $__fcMsgEnabled  = (bool) \App\Models\Setting::get('floating_messenger_enabled', true);
    $__fcMsgUrl      = \App\Models\Setting::get('floating_messenger_url');
    if (empty($__fcMsgUrl)) {
        $fb = \App\Models\Setting::get('social_facebook', '');
        if ($fb) {
            $parts = explode('/', rtrim($fb, '/'));
            $user = end($parts);
            $__fcMsgUrl = 'https://m.me/' . $user;
        } else {
            $__fcMsgUrl = 'https://m.me/masalavalley';
        }
    } elseif (!str_starts_with($__fcMsgUrl, 'http')) {
        $__fcMsgUrl = 'https://m.me/' . ltrim($__fcMsgUrl, '@/');
    }
    $__fcMsgLabel    = \App\Models\Setting::get('floating_messenger_label', 'Messenger');

    // Phone
    $__fcPhoneEnabled= (bool) \App\Models\Setting::get('floating_phone_enabled', true);
    $__fcPhone       = \App\Models\Setting::get('floating_phone_number') ?: \App\Models\Setting::get('contact_phone', '+8801700000000');
    $__fcPhoneUrl    = 'tel:' . preg_replace('/[^\d+]/', '', (string) $__fcPhone);
    $__fcPhoneLabel  = \App\Models\Setting::get('floating_phone_label', 'Call Now');

    // Email
    $__fcMailEnabled = (bool) \App\Models\Setting::get('floating_email_enabled', true);
    $__fcMail        = \App\Models\Setting::get('floating_email_address') ?: \App\Models\Setting::get('contact_email', 'contact@masalavalley.com');
    $__fcMailUrl     = 'mailto:' . $__fcMail;
    $__fcMailLabel   = \App\Models\Setting::get('floating_email_label', 'Email Us');

    $isLeft = ($__fcPosition === 'left');
@endphp

<div id="floating-contact-widget"
     x-data="{ open: false, showTooltip: false }"
     @click.outside="open = false"
     @keydown.escape.window="open = false"
     class="fc-wrapper {{ $isLeft ? 'fc-left' : 'fc-right' }}"
     aria-label="Contact options">

    {{-- Channel items stack (pops up vertically above trigger) --}}
    <div class="fc-channels"
         x-show="open"
         x-transition:enter="fc-transition-in"
         x-transition:enter-start="fc-hidden"
         x-transition:enter-end="fc-visible"
         x-transition:leave="fc-transition-out"
         x-transition:leave-start="fc-visible"
         x-transition:leave-end="fc-hidden"
         x-cloak>

        {{-- 1. WhatsApp --}}
        @if($__fcWaEnabled && $__fcWaClean)
        <a href="{{ $__fcWaUrl }}"
           target="_blank"
           rel="noopener noreferrer"
           class="fc-item fc-whatsapp"
           aria-label="{{ $__fcWaLabel }}"
           style="--order: 4">
            <span class="fc-label {{ $isLeft ? 'fc-label-right' : 'fc-label-left' }}">{{ $__fcWaLabel }}</span>
            <span class="fc-circle fc-bg-whatsapp">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    <path d="M12 2a10 10 0 0 0-8.535 15.15L2 22l4.985-1.393A10 10 0 1 0 12 2z"/>
                </svg>
            </span>
        </a>
        @endif

        {{-- 2. Facebook Messenger --}}
        @if($__fcMsgEnabled && $__fcMsgUrl)
        <a href="{{ $__fcMsgUrl }}"
           target="_blank"
           rel="noopener noreferrer"
           class="fc-item fc-messenger"
           aria-label="{{ $__fcMsgLabel }}"
           style="--order: 3">
            <span class="fc-label {{ $isLeft ? 'fc-label-right' : 'fc-label-left' }}">{{ $__fcMsgLabel }}</span>
            <span class="fc-circle fc-bg-messenger">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                    <path d="M12 2C6.477 2 2 6.145 2 11.258c0 2.91 1.455 5.513 3.735 7.185V22l3.418-1.875c.91.253 1.874.39 2.847.39 5.523 0 10-4.145 10-9.257C22 6.145 17.523 2 12 2zm1.034 12.443l-2.617-2.793-5.105 2.793 5.617-5.962 2.68 2.792 5.043-2.792-5.618 5.962z"/>
                </svg>
            </span>
        </a>
        @endif

        {{-- 3. Direct Call / Phone --}}
        @if($__fcPhoneEnabled && $__fcPhone)
        <a href="{{ $__fcPhoneUrl }}"
           class="fc-item fc-phone"
           aria-label="{{ $__fcPhoneLabel }}"
           style="--order: 2">
            <span class="fc-label {{ $isLeft ? 'fc-label-right' : 'fc-label-left' }}">{{ $__fcPhoneLabel }}</span>
            <span class="fc-circle fc-bg-phone">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
            </span>
        </a>
        @endif

        {{-- 4. Email --}}
        @if($__fcMailEnabled && $__fcMail)
        <a href="{{ $__fcMailUrl }}"
           class="fc-item fc-email"
           aria-label="{{ $__fcMailLabel }}"
           style="--order: 1">
            <span class="fc-label {{ $isLeft ? 'fc-label-right' : 'fc-label-left' }}">{{ $__fcMailLabel }}</span>
            <span class="fc-circle fc-bg-email">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
            </span>
        </a>
        @endif

    </div>

    {{-- Main Trigger Button --}}
    <button type="button"
            @click="open = !open"
            @mouseenter="showTooltip = true"
            @mouseleave="showTooltip = false"
            class="fc-trigger"
            :class="{ 'is-open': open }"
            style="background: {{ $__fcBtnBg }}; color: {{ $__fcBtnIconColor }};"
            aria-label="{{ $__fcTooltip }}"
            :aria-expanded="open.toString()">

        {{-- Default Chat Bubble Icon --}}
        <span class="fc-icon-chat" x-show="!open" x-transition:enter="fc-fade-in">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
            </svg>
        </span>

        {{-- Close "X" Icon when opened --}}
        <span class="fc-icon-close" x-show="open" x-transition:enter="fc-fade-in" x-cloak>
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </span>

        {{-- Blue notification badge dot (from screenshot) --}}
        @if($__fcBadge)
        <span class="fc-badge-dot" title="Online & ready to assist" aria-hidden="true">
            <span class="fc-badge-ping"></span>
        </span>
        @endif
    </button>

    {{-- Hover Tooltip for Main Trigger --}}
    @if($__fcTooltip)
    <div class="fc-main-tooltip {{ $isLeft ? 'fc-tooltip-right' : 'fc-tooltip-left' }}"
         x-show="showTooltip && !open"
         x-transition:enter="fc-fade-in"
         x-transition:leave="fc-fade-out"
         x-cloak>
        {{ $__fcTooltip }}
    </div>
    @endif

</div>

<style>
/* ── Floating Contact Widget Styles ── */
.fc-wrapper {
    position: fixed;
    z-index: 998;
    display: flex;
    flex-direction: column;
    align-items: center;
    bottom: 82px; /* Above mobile bottom-nav bar */
}

@media (min-width: 769px) {
    .fc-wrapper {
        bottom: 30px;
    }
}

.fc-right {
    right: 20px;
}
@media (min-width: 769px) {
    .fc-right {
        right: 28px;
    }
}

.fc-left {
    left: 20px;
}
@media (min-width: 769px) {
    .fc-left {
        left: 28px;
    }
}

/* ── Main Trigger Button ── */
.fc-trigger {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 25px -4px rgba(0, 0, 0, 0.35), 0 4px 10px -2px rgba(0, 0, 0, 0.15);
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

.fc-trigger:hover {
    transform: scale(1.08);
    box-shadow: 0 14px 30px -4px rgba(0, 0, 0, 0.42), 0 6px 12px -2px rgba(0, 0, 0, 0.2);
}

.fc-trigger.is-open {
    transform: rotate(90deg);
}

.fc-trigger:focus-visible {
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.8), 0 0 0 6px rgba(155, 81, 35, 0.6);
}

/* ── Badge Dot (Blue indicator) ── */
.fc-badge-dot {
    position: absolute;
    top: -1px;
    right: -1px;
    width: 14px;
    height: 14px;
    background: #2563eb;
    border: 2.5px solid #ffffff;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.4);
}

.fc-badge-ping {
    position: absolute;
    inset: -3px;
    border-radius: 50%;
    background: #3b82f6;
    opacity: 0.75;
    animation: fc-ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
}

@keyframes fc-ping {
    75%, 100% {
        transform: scale(1.9);
        opacity: 0;
    }
}

/* ── Vertical Channels Stack ── */
.fc-channels {
    position: absolute;
    bottom: 64px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    padding-bottom: 6px;
}

.fc-item {
    display: flex;
    align-items: center;
    text-decoration: none;
    position: relative;
    cursor: pointer;
    outline: none;
    -webkit-tap-highlight-color: transparent;
    transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.fc-item:hover {
    transform: translateY(-2px) scale(1.08);
}

/* ── Circle Icon Buttons ── */
.fc-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.22), 0 2px 6px rgba(0, 0, 0, 0.12);
    transition: box-shadow 0.2s ease;
}

.fc-item:hover .fc-circle {
    box-shadow: 0 10px 22px rgba(0, 0, 0, 0.3);
}

/* Distinct Vibrant Colors matching screenshot */
.fc-bg-whatsapp  { background: #25D366; }
.fc-bg-messenger { background: #0084FF; }
.fc-bg-phone     { background: #FF6600; }
.fc-bg-email     { background: #EA4335; }

/* ── Side Labels ── */
.fc-label {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(15, 23, 42, 0.92);
    color: #ffffff;
    font-size: 12.5px;
    font-weight: 600;
    letter-spacing: 0.2px;
    padding: 5px 12px;
    border-radius: 9999px;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.2s ease, transform 0.2s ease;
    backdrop-filter: blur(6px);
}

.fc-label-left {
    right: 58px;
    transform: translateY(-50%) translateX(6px);
}
.fc-item:hover .fc-label-left {
    opacity: 1;
    transform: translateY(-50%) translateX(0);
}

.fc-label-right {
    left: 58px;
    transform: translateY(-50%) translateX(-6px);
}
.fc-item:hover .fc-label-right {
    opacity: 1;
    transform: translateY(-50%) translateX(0);
}

/* ── Main Tooltip ── */
.fc-main-tooltip {
    position: absolute;
    top: 50%;
    background: rgba(15, 23, 42, 0.94);
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 9999px;
    white-space: nowrap;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
    pointer-events: none;
    backdrop-filter: blur(6px);
    z-index: 999;
}

.fc-tooltip-left {
    right: 66px;
    transform: translateY(-50%);
}

.fc-tooltip-right {
    left: 66px;
    transform: translateY(-50%);
}

/* ── Smooth Alpine Transitions ── */
.fc-transition-in {
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.fc-transition-out {
    transition: all 0.2s ease-in;
}
.fc-hidden {
    opacity: 0;
    transform: translateY(16px) scale(0.9);
}
.fc-visible {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.fc-fade-in {
    transition: opacity 0.2s ease;
}
.fc-fade-out {
    transition: opacity 0.15s ease;
}
</style>
