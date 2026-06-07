@props(['value' => 1])
<div x-data="{ q: {{ $value }} }" class="inline-flex items-center border border-border rounded-lg overflow-hidden">
    <button type="button" aria-label="Decrease quantity" class="w-9 h-9 text-ink hover:bg-cream" @click="q = Math.max(1, q-1); $dispatch('qty', q)">−</button>
    <span class="w-10 text-center text-sm" x-text="q"></span>
    <button type="button" aria-label="Increase quantity" class="w-9 h-9 text-ink hover:bg-cream" @click="q = q+1; $dispatch('qty', q)">+</button>
</div>
