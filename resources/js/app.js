import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('shop', {
  FREE_GIFT_THRESHOLD: 3000,
  FREE_SHIP_THRESHOLD: 1500,

  items: [],   // each {id, name, weight, price, cat, qty}
  wish: [],    // array of product ids
  open: false, // cart drawer
  toastMsg: '',
  toastOn: false,
  _t: null,

  // ── Cart ──────────────────────────────────────────────────
  add(p) {
    const ex = this.items.find(i => i.id === p.id);
    if (ex) {
      ex.qty += 1;
    } else {
      this.items.push({ id: p.id, name: p.name, weight: p.weight, price: p.price, cat: p.cat, qty: 1 });
    }
    this.showToast(p.name + ' added to cart');
  },

  changeQty(id, d) {
    const it = this.items.find(i => i.id === id);
    if (it) it.qty = Math.max(1, it.qty + d);
  },

  remove(id) {
    this.items = this.items.filter(i => i.id !== id);
  },

  buyNow(p) {
    this.add(p);
    window.location.href = '/checkout';
  },

  // ── Getters ───────────────────────────────────────────────
  get count() {
    return this.items.reduce((s, i) => s + i.qty, 0);
  },

  get subtotal() {
    return Math.round(this.items.reduce((s, i) => s + i.price * i.qty, 0));
  },

  get freeShip() {
    return this.subtotal >= this.FREE_SHIP_THRESHOLD;
  },

  get delivery() {
    return this.freeShip ? 0 : 60;
  },

  get total() {
    return this.subtotal + this.delivery;
  },

  get giftPct() {
    return Math.min(100, Math.round(this.subtotal / this.FREE_GIFT_THRESHOLD * 100));
  },

  get giftRemain() {
    return Math.max(0, this.FREE_GIFT_THRESHOLD - this.subtotal);
  },

  // ── Wishlist ──────────────────────────────────────────────
  toggleWish(id) {
    if (this.wish.includes(id)) {
      this.wish = this.wish.filter(x => x !== id);
    } else {
      this.wish.push(id);
    }
  },

  isWished(id) {
    return this.wish.includes(id);
  },

  get wishCount() {
    return this.wish.length;
  },

  // ── Drawer ────────────────────────────────────────────────
  show() { this.open = true; },
  hide() { this.open = false; },
  toggle() { this.open = !this.open; },

  // ── Toast ─────────────────────────────────────────────────
  showToast(msg) {
    this.toastMsg = msg;
    this.toastOn = true;
    clearTimeout(this._t);
    this._t = setTimeout(() => { this.toastOn = false; }, 1900);
  },
});

// ── Window helpers (used inside Blade/Alpine expressions) ────
const CAT_TINT = {
  honey:    '#E7B84B',
  dates:    '#A9682F',
  'oil-ghee': '#D7A53C',
  spices:   '#C0432F',
  nuts:     '#9C7A4D',
  rice:     '#C9B98E',
  mango:    '#E59A2B',
  tea:      '#6E7F4F',
};

window.catTint       = (cat) => CAT_TINT[cat] || '#C9B98E';
window.softBg        = (hex) => hex + '22';
window.tk            = (n)   => '৳' + Number(n || 0).toLocaleString('en-US');
window.discountPct   = (price, oldPrice) => oldPrice ? Math.round((1 - price / oldPrice) * 100) : 0;

Alpine.start();
