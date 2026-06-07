import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('cart', {
  open: false,
  items: [],            // {slug, name, price, image, qty}
  threshold: 1000,      // overwritten by layout from config
  toggle() { this.open = !this.open; },
  show() { this.open = true; },
  hide() { this.open = false; },
  add(product, qty = 1) {
    const found = this.items.find(i => i.slug === product.slug);
    if (found) { found.qty += qty; }
    else { this.items.push({ ...product, qty }); }
    this.show();
  },
  remove(slug) { this.items = this.items.filter(i => i.slug !== slug); },
  setQty(slug, qty) {
    const it = this.items.find(i => i.slug === slug);
    if (it) it.qty = Math.max(1, qty);
  },
  get count() { return this.items.reduce((n, i) => n + i.qty, 0); },
  get total() { return this.items.reduce((s, i) => s + i.price * i.qty, 0); },
  get remaining() { return Math.max(0, this.threshold - this.total); },
  get giftProgress() { return Math.min(100, this.threshold ? (this.total / this.threshold) * 100 : 0); },
});

Alpine.start();
