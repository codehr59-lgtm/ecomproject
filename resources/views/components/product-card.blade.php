@props(['product'])
<div class="group bg-white border border-border rounded-sm p-2 hover:shadow-card transition flex flex-col">
    <div class="relative">
        @if(!empty($product['badge']))
            <x-badge :type="$product['badge']['type']" :label="$product['badge']['label']" class="absolute top-1 left-1 z-10" />
        @endif
        <a href="{{ route('product', $product['slug']) }}">
            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full aspect-square object-cover rounded-sm">
        </a>
    </div>
    <a href="{{ route('product', $product['slug']) }}" class="mt-2 text-sm text-ink font-medium line-clamp-2 min-h-[2.5rem]">{{ $product['name'] }}</a>
    <div class="mt-1">
        <x-price :price="$product['price']" :old="$product['old_price'] ?? null" />
    </div>
    <button type="button"
            class="btn-outline mt-3 w-full text-[13px]"
            @click="$store.cart.add({{ \Illuminate\Support\Js::from([
                'slug' => $product['slug'], 'name' => $product['name'],
                'price' => $product['price'], 'image' => $product['image'],
            ]) }})">
        Add To Cart
    </button>
</div>
