@props(['model' => 'qty'])
<div class="qty">
  <button type="button" @click="{{ $model }} = Math.max(1, {{ $model }} - 1)" aria-label="Decrease">−</button>
  <span x-text="{{ $model }}"></span>
  <button type="button" @click="{{ $model }}++" aria-label="Increase">+</button>
</div>
