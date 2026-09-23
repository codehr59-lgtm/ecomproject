<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Quick Actions</x-slot>

        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px;">

            <a href="{{ route('filament.admin.resources.products.create') }}"
               class="qa-card qa-amber">
                <x-heroicon-o-plus-circle class="qa-icon"/>
                <span class="qa-label">Add Product</span>
            </a>

            <a href="{{ route('filament.admin.resources.products.index') }}"
               class="qa-card qa-indigo">
                <x-heroicon-o-shopping-bag class="qa-icon"/>
                <span class="qa-label">All Products</span>
            </a>

            <a href="{{ route('filament.admin.resources.orders.index') }}"
               class="qa-card qa-green">
                <x-heroicon-o-shopping-cart class="qa-icon"/>
                <span class="qa-label">View Orders</span>
            </a>

            <a href="{{ route('filament.admin.resources.categories.create') }}"
               class="qa-card qa-sky">
                <x-heroicon-o-tag class="qa-icon"/>
                <span class="qa-label">Add Category</span>
            </a>

            <a href="{{ route('filament.admin.resources.subcategories.create') }}"
               class="qa-card qa-purple">
                <x-heroicon-o-squares-2x2 class="qa-icon"/>
                <span class="qa-label">Add Subcategory</span>
            </a>

            <a href="/" target="_blank"
               class="qa-card qa-rose">
                <x-heroicon-o-globe-alt class="qa-icon"/>
                <span class="qa-label">Visit Site</span>
            </a>

        </div>
    </x-filament::section>

    <style>
        .qa-card {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 10px; padding: 20px 12px; border-radius: 12px; border: 1px solid;
            text-decoration: none; transition: background 0.15s, border-color 0.15s, box-shadow 0.15s;
        }
        .qa-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .qa-icon { width: 28px; height: 28px; }
        .qa-label { font-size: 0.78rem; font-weight: 700; text-align: center; }

        /* Light mode colors */
        .qa-amber  { background: rgba(251,191,36,0.08); border-color: rgba(251,191,36,0.25); }
        .qa-amber:hover  { background: rgba(251,191,36,0.15); border-color: rgba(251,191,36,0.5); }
        .qa-amber .qa-icon { color: rgb(217,119,6); }
        .qa-amber .qa-label { color: rgb(146,64,14); }

        .qa-indigo { background: rgba(99,102,241,0.08); border-color: rgba(99,102,241,0.25); }
        .qa-indigo:hover { background: rgba(99,102,241,0.15); border-color: rgba(99,102,241,0.5); }
        .qa-indigo .qa-icon { color: rgb(79,70,229); }
        .qa-indigo .qa-label { color: rgb(55,48,163); }

        .qa-green  { background: rgba(34,197,94,0.08); border-color: rgba(34,197,94,0.25); }
        .qa-green:hover  { background: rgba(34,197,94,0.15); border-color: rgba(34,197,94,0.5); }
        .qa-green .qa-icon { color: rgb(22,163,74); }
        .qa-green .qa-label { color: rgb(21,128,61); }

        .qa-sky    { background: rgba(14,165,233,0.08); border-color: rgba(14,165,233,0.25); }
        .qa-sky:hover    { background: rgba(14,165,233,0.15); border-color: rgba(14,165,233,0.5); }
        .qa-sky .qa-icon { color: rgb(2,132,199); }
        .qa-sky .qa-label { color: rgb(3,105,161); }

        .qa-purple { background: rgba(168,85,247,0.08); border-color: rgba(168,85,247,0.25); }
        .qa-purple:hover { background: rgba(168,85,247,0.15); border-color: rgba(168,85,247,0.5); }
        .qa-purple .qa-icon { color: rgb(147,51,234); }
        .qa-purple .qa-label { color: rgb(107,33,168); }

        .qa-rose   { background: rgba(244,63,94,0.08); border-color: rgba(244,63,94,0.25); }
        .qa-rose:hover   { background: rgba(244,63,94,0.15); border-color: rgba(244,63,94,0.5); }
        .qa-rose .qa-icon { color: rgb(225,29,72); }
        .qa-rose .qa-label { color: rgb(159,18,57); }

        /* Dark mode overrides */
        .dark .qa-amber  { background: rgba(251,191,36,0.10); }
        .dark .qa-amber:hover  { background: rgba(251,191,36,0.18); }
        .dark .qa-amber .qa-icon { color: rgb(251,191,36); }
        .dark .qa-amber .qa-label { color: rgb(253,224,71); }

        .dark .qa-indigo { background: rgba(99,102,241,0.10); }
        .dark .qa-indigo:hover { background: rgba(99,102,241,0.18); }
        .dark .qa-indigo .qa-icon { color: rgb(129,140,248); }
        .dark .qa-indigo .qa-label { color: rgb(165,180,252); }

        .dark .qa-green  { background: rgba(34,197,94,0.10); }
        .dark .qa-green:hover  { background: rgba(34,197,94,0.18); }
        .dark .qa-green .qa-icon { color: rgb(74,222,128); }
        .dark .qa-green .qa-label { color: rgb(134,239,172); }

        .dark .qa-sky    { background: rgba(14,165,233,0.10); }
        .dark .qa-sky:hover    { background: rgba(14,165,233,0.18); }
        .dark .qa-sky .qa-icon { color: rgb(56,189,248); }
        .dark .qa-sky .qa-label { color: rgb(125,211,252); }

        .dark .qa-purple { background: rgba(168,85,247,0.10); }
        .dark .qa-purple:hover { background: rgba(168,85,247,0.18); }
        .dark .qa-purple .qa-icon { color: rgb(192,132,252); }
        .dark .qa-purple .qa-label { color: rgb(216,180,254); }

        .dark .qa-rose   { background: rgba(244,63,94,0.10); }
        .dark .qa-rose:hover   { background: rgba(244,63,94,0.18); }
        .dark .qa-rose .qa-icon { color: rgb(251,113,133); }
        .dark .qa-rose .qa-label { color: rgb(253,164,175); }
    </style>
</x-filament-widgets::widget>
