@extends('layouts.app')

@php
$title    = ucwords(str_replace('-', ' ', $slug));
$cat      = 'Journal';
$author   = 'Shuvo Team';
$date     = 'June 2026';
$readTime = '6 min read';

// Pick a contextual photo category based on slug keywords
$photoMap = [
    'honey'    => 'honey',
    'dates'    => 'dates',
    'ghee'     => 'oil-ghee',
    'oil'      => 'oil-ghee',
    'mustard'  => 'oil-ghee',
    'rice'     => 'rice',
    'mango'    => 'mango',
    'spice'    => 'spices',
    'tea'      => 'tea',
    'nut'      => 'nuts',
];
$photoCat = 'honey';
foreach ($photoMap as $kw => $v) {
    if (str_contains($slug, $kw)) { $photoCat = $v; break; }
}
@endphp

@section('title', $title . ' — Shuvo Journal')

@section('content')

{{-- PAGE HEAD --}}
<div class="page-head">
  <div class="wrap">
    <div class="crumbs">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
      <a href="{{ route('blog') }}">Journal</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
      <span>{{ $title }}</span>
    </div>
    <h1>{{ $title }}</h1>
  </div>
</div>

{{-- ARTICLE --}}
<div class="section">
  <div class="wrap">
    <div style="max-width:760px; margin:0 auto;">

      {{-- Hero image --}}
      <div style="border-radius:var(--radius-l); overflow:hidden; aspect-ratio:16/7; margin-bottom:32px; border:1px solid var(--line);">
        <x-photo :cat="$photoCat" label="JOURNAL" />
      </div>

      {{-- Category + meta --}}
      <div style="display:flex; align-items:center; gap:16px; margin-bottom:18px; flex-wrap:wrap;">
        <span class="eyebrow">{{ $cat }}</span>
        <div class="pcard-meta">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          <span>{{ $author }}</span>
          <span>·</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <span>{{ $date }}</span>
          <span>·</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <span>{{ $readTime }}</span>
        </div>
      </div>

      {{-- Article body --}}
      <div style="font-size:16.5px; line-height:1.78; color:var(--ink-soft);">

        <p style="margin-bottom:22px;">
          Every ingredient we carry at Shuvo comes with a story — usually one that starts long before it reaches
          our warehouse shelves. It begins in the hands of a farmer who has spent decades learning the land,
          a beekeeper who understands the rhythms of the forest, or a miller who still grinds by stone because
          machines simply don't produce the same result. That story is exactly what we want to share with you.
        </p>

        <h2 style="font-size:24px; letter-spacing:-.015em; color:var(--ink); margin:32px 0 16px;">
          Why This Matters to Your Table
        </h2>

        <p style="margin-bottom:22px;">
          When food is sourced with care, the difference shows up immediately — in flavour, texture and the way
          your body responds. Raw, unprocessed ingredients carry their full complement of enzymes, vitamins and
          beneficial compounds intact. They haven't been heated to extend shelf life, diluted with cheaper
          alternatives or coated in preservatives that help them survive a long supply chain. What you taste is
          what nature intended.
        </p>

        <p style="margin-bottom:22px;">
          For centuries, Bangladeshi cooking has relied on ingredients with extraordinary depth — the sharp
          pungency of freshly pressed mustard oil, the complex sweetness of forest honey, the floral aroma of
          Kataribhog rice steamed on a winter morning. We source every product with the goal of preserving that
          depth, and testing it scientifically so you don't have to take our word for it.
        </p>

        <h2 style="font-size:24px; letter-spacing:-.015em; color:var(--ink); margin:32px 0 16px;">
          How We Source and Verify
        </h2>

        <p style="margin-bottom:22px;">
          Our sourcing team visits every new supplier in person before we list a single product. We look at
          how the crop is grown or the product is made, what inputs are used, and whether the producer shares
          our commitment to purity. After onboarding, each batch is tested by an accredited third-party
          laboratory for adulteration, moisture, microbial count and — where relevant — pesticide residue.
          The reports are published on every product page so you can read them yourself.
        </p>

        <p style="margin-bottom:22px;">
          We believe the food system works best when the people eating food can trace it back to the people
          growing it. Transparency isn't just a marketing word for us — it's the foundation of every
          relationship we build with producers and customers alike.
        </p>

        {{-- Inline tip block --}}
        <div style="background:var(--honey-soft); border-left:4px solid var(--honey); border-radius:0 var(--radius-s) var(--radius-s) 0; padding:18px 20px; margin:30px 0;">
          <p style="margin:0; font-size:15px; color:var(--honey-deep);">
            <strong>Shuvo Tip:</strong> When buying any whole food — honey, oil, dates or ghee — always check
            whether it has been heat-treated. Heat above 40°C destroys many of the beneficial compounds that
            make these ingredients special. Look for the words "raw," "cold-pressed" or "unrefined."
          </p>
        </div>

        <p style="margin-bottom:22px;">
          The next time you open a jar of Shuvo Sundarban Honey and see that thick, uneven crystallisation at the
          bottom — that's not a defect. That's proof you're holding the real thing. Raw honey always crystallises.
          Adulterated honey often doesn't.
        </p>

        <p style="margin-bottom:22px;">
          We're grateful you've taken the time to read this far. Our journal exists to share knowledge, not just
          sell products. If you have questions about any ingredient we carry, write to us at hello@shuvo.com —
          our team personally answers every message.
        </p>

      </div>

      {{-- Back link --}}
      <div style="margin-top:40px; padding-top:28px; border-top:1px solid var(--line);">
        <a href="{{ route('blog') }}" class="btn btn-ghost">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
          </svg>
          Back to Journal
        </a>
      </div>

    </div>
  </div>
</div>

@endsection
