@props(['title', 'products', 'viewAll' => null, 'variant' => 'underline'])
<section class="max-w-content mx-auto px-4 my-12">
    <div class="flex items-center justify-between">
        <x-section-heading :variant="$variant">{{ $title }}</x-section-heading>
        @if($viewAll)
            <a href="{{ $viewAll }}" class="text-primary font-semibold text-sm">VIEW ALL →</a>
        @endif
    </div>
    <div class="flex gap-4 overflow-x-auto pb-2 snap-x">
        @foreach($products as $product)
            <div class="snap-start shrink-0 w-44 sm:w-52">
                <x-product-card :product="$product" />
            </div>
        @endforeach
    </div>
</section>
