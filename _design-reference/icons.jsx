/* Shuvo — icons + small shared helpers (Babel/JSX) */
const { useState, useEffect, useRef, useMemo } = React;

// ---- formatting ----
function tk(n) { return "৳" + n.toLocaleString("en-US"); }

// ---- icon set (stroke, 24 grid) ----
const ICONS = {
  search: "M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm10 2-4.3-4.3",
  cart: "M6 6h15l-1.5 9h-12L5 3H2 M9 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm9 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z",
  heart: "M12 20s-7-4.5-9.5-9A4.7 4.7 0 0 1 12 6a4.7 4.7 0 0 1 9.5 5c-2.5 4.5-9.5 9-9.5 9Z",
  user: "M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 8a7 7 0 0 1 14 0",
  menu: "M3 6h18M3 12h18M3 18h18",
  grid: "M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z",
  close: "M6 6l12 12M18 6 6 18",
  plus: "M12 5v14M5 12h14",
  minus: "M5 12h14",
  arrowR: "M5 12h14M13 6l6 6-6 6",
  chevR: "M9 6l6 6-6 6",
  chevD: "M6 9l6 6 6-6",
  check: "M5 12l5 5L20 6",
  star: "M12 3l2.6 5.6 6 .7-4.5 4.1 1.2 6-5.3-3-5.3 3 1.2-6L3.4 9.3l6-.7Z",
  truck: "M3 6h11v9H3zM14 9h4l3 3v3h-7M7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm10 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z",
  leaf: "M5 21c0-7 4-13 14-14 0 9-5 14-14 14ZM5 21c2-5 5-8 9-10",
  shield: "M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6Z",
  badge: "M12 2l2.5 2 3.5-.5L18.5 7 22 8.5 20 12l2 3.5-3.5 1.5-.5 3.5L14 20l-2 2-2-2-3.5.5L6 17 2.5 15.5 4 12 2 8.5 5.5 7 6 3.5 9.5 4Z",
  refresh: "M4 12a8 8 0 0 1 14-5l2 2M20 12a8 8 0 0 1-14 5l-2-2M18 4v5h-5M6 20v-5h5",
  gift: "M20 9H4v3h16zM12 9v12M12 9S10 4 7 5s1 4 5 4Zm0 0s2-5 5-4-1 4-5 4ZM5 12v9h14v-9",
  phone: "M5 4h4l1.5 5-2.5 1.5a11 11 0 0 0 5 5L19 13l5 1.5V19a2 2 0 0 1-2 2A16 16 0 0 1 5 4Z",
  pin: "M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11Zm0-8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z",
  mail: "M3 6h18v12H3zM3 7l9 6 9-6",
  filter: "M3 5h18l-7 8v6l-4-2v-4z",
  sort: "M7 4v16M7 20l-3-3M7 4l3 3M17 20V4M17 4l3 3M17 20l-3-3",
  bag: "M6 7h12l1 13H5zM9 7a3 3 0 0 1 6 0",
  tag: "M3 12V4h8l9 9-7 7-9-9Zm5-4a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z",
  clock: "M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-14v5l3 2",
  spark: "M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18",
  fb: "M14 9h3V6h-3c-1.7 0-3 1.3-3 3v2H8v3h3v6h3v-6h2.5l.5-3H14V9.5c0-.3.2-.5.5-.5Z",
  ig: "M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4Zm5 5a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm5-1h0",
  x: "M4 4l16 16M20 4 4 20",
};

function Icon({ name, size, fill, stroke, sw }) {
  const d = ICONS[name] || "";
  return (
    <svg viewBox="0 0 24 24" width={size || 24} height={size || 24}
         fill={fill || "none"} stroke={stroke || "currentColor"}
         strokeWidth={sw || 1.8} strokeLinecap="round" strokeLinejoin="round">
      {d.split(" M").map((seg, i) => <path key={i} d={(i ? "M" : "") + seg} />)}
    </svg>
  );
}

function Stars({ value, size }) {
  return (
    <span className="stars">
      {[0,1,2,3,4].map(i => (
        <Icon key={i} name="star" size={size || 13}
              fill={i < Math.round(value) ? "currentColor" : "none"} sw={1.4} />
      ))}
    </span>
  );
}

// category tint -> soft background for placeholders
function catTint(catId) {
  const c = (window.SHUVO.categories.find(c => c.id === catId) || {}).tint || "#cccccc";
  return c;
}
function softBg(hex) {
  // mix hex with white ~ 78%
  const h = hex.replace("#",""); const r = parseInt(h.slice(0,2),16), g = parseInt(h.slice(2,4),16), b = parseInt(h.slice(4,6),16);
  const mix = (c) => Math.round(c + (255 - c) * 0.74);
  return `rgb(${mix(r)},${mix(g)},${mix(b)})`;
}

// Placeholder product "photo"
function Photo({ product, kind }) {
  const tint = catTint(product.cat);
  const bg = softBg(tint);
  const cat = window.SHUVO.categories.find(c => c.id === product.cat);
  return (
    <div className="ph" style={{ "--ph-bg": bg }}>
      <div className="ph-inner">
        <div className="ph-jar" style={{ borderColor: "rgba(255,255,255,.85)", background: tint + "44" }}></div>
        <div className="ph-label">{cat ? cat.name : "Product"}<br/>{product.weight}</div>
      </div>
    </div>
  );
}

Object.assign(window, { tk, Icon, Stars, Photo, catTint, softBg });
