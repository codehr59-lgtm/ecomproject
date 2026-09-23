import './bootstrap';
import Alpine from 'alpinejs';
console.log('[Shuvo] JS loaded v3');

window.Alpine = Alpine;

Alpine.store('shop', {
  FREE_GIFT_THRESHOLD: (window.GIFT_CONFIG && window.GIFT_CONFIG.min) || 3000,
  giftEnabled: (window.GIFT_CONFIG ? window.GIFT_CONFIG.enabled : true),
  giftName: (window.GIFT_CONFIG && window.GIFT_CONFIG.name) || 'free Lychee Honey sachet',
  giftSuccessMsg: (window.GIFT_CONFIG && window.GIFT_CONFIG.successMsg) || "🎉 You've unlocked a free gift! It'll be added at checkout.",
  FREE_SHIP_THRESHOLD: (window.DELIVERY_CONFIG && window.DELIVERY_CONFIG.freeMin) || 1500,
  DELIVERY_INSIDE: (window.DELIVERY_CONFIG && window.DELIVERY_CONFIG.inside) || 60,
  DELIVERY_OUTSIDE: (window.DELIVERY_CONFIG && window.DELIVERY_CONFIG.outside) || 120,

  items: [],   // each {id, name, weight, price, cat, qty}
  wish: [],    // array of product ids
  open: false, // cart drawer
  toastMsg: '',
  toastOn: false,
  _t: null,

  // ── Init (Alpine calls this automatically) ────────────────────────────
  init() {
    const saved = localStorage.getItem('shuvo_cart');
    if (saved) {
      try { this.items = JSON.parse(saved); } catch(e) {}
    }
    if (window.WISHLIST && window.WISHLIST.length > 0) {
      this.wish = window.WISHLIST.map(Number);
    }
  },

  _persist() {
    localStorage.setItem('shuvo_cart', JSON.stringify(this.items));
  },

  // ── Cart ──────────────────────────────────────────────────
  add(p, qty) {
    if (p.is_combo) {
      return this.addCombo(p, qty);
    }
    console.log('[Shuvo] add() called with:', JSON.stringify(p), 'qty:', qty);
    const pid = Number(p.id);
    const addQty = Number(qty) || 1;
    const varId = p.variation_id ? Number(p.variation_id) : null;
    const weight = p.weight || '';
    const itemKey = varId ? `${pid}_v_${varId}` : (weight ? `${pid}_${weight}` : `${pid}`);

    const idx = this.items.findIndex(i => (i.itemKey || (i.variation_id ? `${i.id}_v_${i.variation_id}` : (i.weight ? `${i.id}_${i.weight}` : `${i.id}`))) === itemKey);
    if (idx > -1) {
      const updated = [...this.items];
      updated[idx] = { ...updated[idx], qty: updated[idx].qty + addQty };
      this.items = updated;
    } else {
      this.items = [...this.items, {
        id: pid,
        itemKey: itemKey,
        variation_id: varId,
        name: p.name,
        weight: weight,
        price: Number(p.price),
        cat: p.cat,
        image: p.image || null,
        qty: addQty
      }];
    }
    console.log('[Shuvo] items now:', this.items.length);
    this._persist();
    if (window.ttq) { ttq.track('AddToCart', { content_id: String(pid), content_name: p.name, content_type: 'product', quantity: addQty, price: Number(p.price), value: Number(p.price) * addQty, currency: 'BDT' }); }
    if (window.fbq) { fbq('track', 'AddToCart', { content_ids: [String(pid)], content_name: p.name, content_type: 'product', value: Number(p.price) * addQty, currency: 'BDT' }); }
    this.showToast(p.name + ' added to cart');
    this.open = true;
  },

  addCombo(c, qty) {
    console.log('[Shuvo] addCombo() called with:', JSON.stringify(c), 'qty:', qty);
    const cid = Number(c.id);
    const addQty = Number(qty) || 1;
    const itemKey = `combo_${cid}`;

    const idx = this.items.findIndex(i => (i.itemKey === itemKey));
    if (idx > -1) {
      const updated = [...this.items];
      updated[idx] = { ...updated[idx], qty: updated[idx].qty + addQty };
      this.items = updated;
    } else {
      this.items = [...this.items, {
        id: cid,
        combo_id: cid,
        is_combo: true,
        itemKey: itemKey,
        name: c.name,
        weight: c.items || 'Combo Package',
        price: Number(c.price),
        old_price: c.old_price ? Number(c.old_price) : null,
        image: c.image || null,
        cat: 'combo',
        qty: addQty
      }];
    }
    this._persist();
    this.showToast(c.name + ' (Combo Pack) added to cart');
    this.open = true;
  },

  buyComboNow(c, qty) {
    this.addCombo(c, qty);
    window.location.href = '/checkout';
  },

  changeQty(keyOrId, d) {
    this.items = this.items.map(i => {
      const match = (i.itemKey && i.itemKey === keyOrId) || String(i.id) === String(keyOrId);
      return match ? { ...i, qty: Math.max(1, i.qty + d) } : i;
    });
    this._persist();
  },

  remove(keyOrId) {
    this.items = this.items.filter(i => {
      const match = (i.itemKey && i.itemKey === keyOrId) || String(i.id) === String(keyOrId);
      return !match;
    });
    this._persist();
  },

  buyNow(p, qty) {
    this.add(p, qty);
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
    return this.freeShip ? 0 : this.DELIVERY_INSIDE;
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

// ── Wishlist persistence helper ──────────────────────────────────────────
window.persistWish = (id) => {
  if (!window.AUTH) return;
  const token = document.querySelector('meta[name=csrf-token]');
  if (!token) return;
  fetch('/wishlist/toggle/' + id, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': token.content,
      'Accept': 'application/json',
    },
  });
};

window.catTint       = (cat) => CAT_TINT[cat] || '#C9B98E';
window.softBg        = (hex) => hex + '22';
window.tk            = (n)   => '৳' + Number(n || 0).toLocaleString('en-US');
window.discountPct   = (price, oldPrice) => oldPrice ? Math.round((1 - price / oldPrice) * 100) : 0;

Alpine.start();
