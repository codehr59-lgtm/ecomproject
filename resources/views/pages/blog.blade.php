@extends('layouts.app')
@section('title', 'Blog — Shuvo')

@php
$posts = [
    [
        'slug'    => 'the-truth-about-raw-honey',
        'title'   => 'The Truth About Raw Honey',
        'excerpt' => 'Most supermarket honey has been heated above 40°C, destroying enzymes and antioxidants. Here is how to tell real raw honey from the processed kind.',
        'cat'     => 'Honey',
        'date'    => 'May 12, 2026',
        'read'    => '5 min read',
        'photo'   => 'honey',
    ],
    [
        'slug'    => 'how-to-store-dates',
        'title'   => 'How to Store Dates for Maximum Freshness',
        'excerpt' => 'Ajwa and Medjool dates can last up to a year if stored correctly. We share the exact method our warehouse team uses — no freezer required.',
        'cat'     => 'Dates',
        'date'    => 'May 2, 2026',
        'read'    => '4 min read',
        'photo'   => 'dates',
    ],
    [
        'slug'    => 'why-cold-pressed-mustard-oil',
        'title'   => 'Why Cold-Pressed Mustard Oil?',
        'excerpt' => 'Refined oils are stripped of flavour and nutrition. Cold-pressed mustard oil retains glucosinolates, omega-3 and that signature pungent warmth.',
        'cat'     => 'Oils & Ghee',
        'date'    => 'Apr 22, 2026',
        'read'    => '6 min read',
        'photo'   => 'oil-ghee',
    ],
    [
        'slug'    => '5-ways-to-use-ghee',
        'title'   => '5 Delicious Ways to Use Pure Ghee',
        'excerpt' => 'Beyond flatbread and biryani — ghee is a versatile cooking fat with a high smoke point, perfect for everything from sautéed vegetables to bulletproof coffee.',
        'cat'     => 'Oils & Ghee',
        'date'    => 'Apr 14, 2026',
        'read'    => '5 min read',
        'photo'   => 'oil-ghee',
    ],
    [
        'slug'    => 'choosing-the-right-rice',
        'title'   => 'Choosing the Right Rice for Every Dish',
        'excerpt' => 'Kalijira, Kataribhog, Miniket, Nazirshail — Bangladesh has dozens of aromatic rice varieties. A guide to picking the right grain for polao, biryani and everyday cooking.',
        'cat'     => 'Rice',
        'date'    => 'Apr 5, 2026',
        'read'    => '7 min read',
        'photo'   => 'rice',
    ],
    [
        'slug'    => 'mango-season-guide',
        'title'   => 'Mango Season Guide: Himsagar to Langra',
        'excerpt' => "Bangladesh's mango season runs May through August. We break down the varieties by sweetness, fibre and ideal use — so you can pre-order with confidence.",
        'cat'     => 'Mango',
        'date'    => 'Mar 28, 2026',
        'read'    => '6 min read',
        'photo'   => 'mango',
    ],
];
@endphp

@section('content')

{{-- PAGE HEAD --}}
<div class="page-head">
  <div class="wrap">
    <div class="crumbs">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
      <span>Journal</span>
    </div>
    <h1>Journal</h1>
    <p class="sub">Recipes, sourcing stories &amp; wellness — straight from our farm-to-table world.</p>
  </div>
</div>

{{-- BLOG GRID --}}
<div class="section">
  <div class="wrap">

    <div class="grid-4" style="grid-template-columns:repeat(3,1fr);">
      @foreach($posts as $post)
        <article class="pcard">
          <div class="pcard-media">
            <x-photo :cat="$post['photo']" label="JOURNAL" />
          </div>
          <div class="pcard-body">
            <span class="pcard-cat">{{ $post['cat'] }}</span>
            <h3 class="pcard-title">
              <a href="{{ route('blog.post', $post['slug']) }}" class="pcard-title">
                {{ $post['title'] }}
              </a>
            </h3>
            <p style="font-size:14px; color:var(--ink-soft); line-height:1.6; margin:0; flex:1;">
              {{ $post['excerpt'] }}
            </p>
            <div class="pcard-meta" style="margin-top:auto;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <span>{{ $post['date'] }}</span>
              <span>·</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              <span>{{ $post['read'] }}</span>
            </div>
          </div>
        </article>
      @endforeach
    </div>

  </div>
</div>

@endsection
