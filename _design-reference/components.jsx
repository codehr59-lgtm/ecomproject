/* Shuvo — shared components (Babel/JSX) */

const FREE_GIFT_THRESHOLD = 3000;
const FREE_SHIP_THRESHOLD = 1500;

function BrandMark() {
  return (
    <span className="brand-mark"><Icon name="leaf" size={22} sw={1.7} /></span>
  );
}

function Logo({ onNav, footer }) {
  return (
    <a className="brand" href="#" onClick={(e)=>{e.preventDefault(); onNav && onNav({page:"home"});}}>
      <BrandMark />
      <span>
        <span className="brand-name">Shuvo<b>.</b></span>
        {!footer && <span className="brand-tag" style={{display:"block"}}>Pure · Organic · Halal</span>}
      </span>
    </a>
  );
}

function discountPct(p) {
  if (!p.oldPrice) return 0;
  return Math.round((1 - p.price / p.oldPrice) * 100);
}

function CardBadges({ p }) {
  const pct = discountPct(p);
  return (
    <div className="pcard-badges">
      {pct > 0 && <span className="badge badge-save">Save {pct}%</span>}
      {p.badge === "new" && <span className="badge badge-new">New</span>}
      {p.badge === "preorder" && <span className="badge badge-pre">Pre-order</span>}
    </div>
  );
}

// ---------------- HEADER ----------------
function Header({ cartCount, wishCount, onCartOpen, onNav, query, setQuery, activeCat }) {
  const cats = window.SHUVO.categories;
  return (
    <header className="hdr">
      <div className="announce">
        <Icon name="truck" size={15} />
        <span>Free delivery over <strong>{tk(FREE_SHIP_THRESHOLD)}</strong> in Dhaka</span>
        <span className="dot"></span>
        <span>Cash on delivery available</span>
        <span className="dot"></span>
        <span>Add <strong>{tk(FREE_GIFT_THRESHOLD)}</strong> &amp; unlock a free gift</span>
      </div>

      <div className="wrap">
        <div className="hdr-main">
          <Logo onNav={onNav} />
          <div className="search" onClick={(e)=>e.currentTarget.querySelector("input").focus()}>
            <Icon name="search" size={18} />
            <input
              placeholder="Search honey, dates, ghee…"
              value={query}
              onChange={(e)=>setQuery(e.target.value)}
              onKeyDown={(e)=>{ if(e.key==="Enter") onNav({page:"shop", search:true}); }}
            />
          </div>
          <div className="hdr-actions">
            <button className="icon-btn" title="Track order" onClick={()=>onNav({page:"shop"})}>
              <Icon name="truck" size={20} />
              <span className="icon-label">Track</span>
            </button>
            <button className="icon-btn" title="Account">
              <Icon name="user" size={20} />
              <span className="icon-label">Sign in</span>
            </button>
            <button className="icon-btn" title="Wishlist">
              <Icon name="heart" size={20} />
              {wishCount > 0 && <span className="count">{wishCount}</span>}
              <span className="icon-label">Wishlist</span>
            </button>
            <button className="icon-btn" title="Cart" onClick={onCartOpen}>
              <Icon name="cart" size={20} />
              {cartCount > 0 && <span className="count">{cartCount}</span>}
              <span className="icon-label">Cart</span>
            </button>
          </div>
        </div>
      </div>

      <nav className="nav">
        <div className="wrap">
          <div className="nav-row">
            <button className="nav-all" onClick={()=>onNav({page:"shop"})}>
              <Icon name="grid" size={16} /> All Categories
            </button>
            <a className="nav-link hot" href="#" onClick={(e)=>{e.preventDefault();onNav({page:"shop",cat:"all",deal:true});}}>Offer Zone</a>
            {cats.map(c => (
              <a key={c.id} className={"nav-link" + (activeCat===c.id ? " active":"")}
                 href="#" onClick={(e)=>{e.preventDefault(); onNav({page:"shop", cat:c.id});}}>
                {c.name}
              </a>
            ))}
          </div>
        </div>
      </nav>
    </header>
  );
}

// ---------------- PRODUCT CARD ----------------
function ProductCard({ p, onAdd, onOpen, wished, onWish, onBuy, buyNow }) {
  const [justAdded, setJustAdded] = useState(false);
  const save = p.oldPrice ? p.oldPrice - p.price : 0;
  const add = (e) => {
    e.stopPropagation();
    onAdd(p);
    setJustAdded(true);
    setTimeout(()=>setJustAdded(false), 1100);
  };
  return (
    <div className="pcard" onClick={()=>onOpen(p)}>
      <div className="pcard-media">
        <CardBadges p={p} />
        {p.badge === "best" && <span className="ribbon-best">Best Selling</span>}
        <button className={"pcard-wish" + (wished?" on":"")} onClick={(e)=>{e.stopPropagation(); onWish(p);}} title="Wishlist">
          <Icon name="heart" size={17} fill={wished?"currentColor":"none"} />
        </button>
        <Photo product={p} />
      </div>
      <div className="pcard-body">
        <h3 className="pcard-title">{p.name}</h3>
        <div className="price-row">
          <span className="price"><span className="tk">৳</span>{p.price.toLocaleString()}</span>
          {p.oldPrice && <span className="price-old">{tk(p.oldPrice)}</span>}
          {save>0 && <span className="save-pill">Save {tk(save)}</span>}
        </div>
        <div className="pcard-foot">
          <button className={"add-btn" + (justAdded?" added":"")} onClick={add}>
            <Icon name={justAdded?"check":"cart"} size={16} />
            <span>{justAdded ? "Added" : "Add To Cart"}</span>
          </button>
          {buyNow && <button className="buy-btn" onClick={(e)=>{e.stopPropagation(); onBuy && onBuy(p);}}>Buy now</button>}
        </div>
      </div>
    </div>
  );
}

// ---------------- CART DRAWER ----------------
function CartDrawer({ open, items, onClose, onQty, onRemove, onNav, onCheckout }) {
  const subtotal = items.reduce((s,i)=>s + i.price * i.qty, 0);
  const giftPct = Math.min(100, Math.round(subtotal / FREE_GIFT_THRESHOLD * 100));
  const remain = Math.max(0, FREE_GIFT_THRESHOLD - subtotal);
  const freeShip = subtotal >= FREE_SHIP_THRESHOLD;
  const count = items.reduce((s,i)=>s+i.qty,0);

  return (
    <React.Fragment>
      <div className={"overlay" + (open?" on":"")} onClick={onClose}></div>
      <aside className={"drawer" + (open?" on":"")} aria-hidden={!open}>
        <div className="drawer-head">
          <h3><Icon name="cart" size={20} /> Your Cart {count>0 && <span style={{color:"var(--muted)",fontWeight:500,fontSize:15}}>({count})</span>}</h3>
          <button className="x" onClick={onClose}><Icon name="close" size={20} /></button>
        </div>

        {items.length > 0 && (
          <div className="gift-bar">
            <p>{remain > 0
              ? <span>Add <b>{tk(remain)}</b> more to unlock a <b>free Lychee Honey sachet</b> 🎁</span>
              : <span>🎉 You've unlocked a <b>free gift</b>! It'll be added at checkout.</span>}</p>
            <div className="gift-track"><div className="gift-fill" style={{width: giftPct+"%"}}></div></div>
          </div>
        )}

        <div className="drawer-body app-scroll">
          {items.length === 0 ? (
            <div className="cart-empty">
              <div className="cart-empty-ico"><Icon name="bag" size={38} /></div>
              <h4>Your cart is empty</h4>
              <p>Fresh, organic and ready to ship.</p>
              <button className="btn btn-primary" onClick={()=>{onClose(); onNav({page:"shop"});}}>Start shopping</button>
            </div>
          ) : items.map(it => (
            <div className="cart-line" key={it.id}>
              <div className="cart-line-art" style={{"--ph-bg": softBg(catTint(it.cat))}}>
                <div className="ph-jar" style={{width:34,height:40,margin:0,background:catTint(it.cat)+"44"}}></div>
              </div>
              <div className="cart-line-info">
                <h5>{it.name}</h5>
                <span className="w">{it.weight}</span>
                <div className="cart-line-bottom">
                  <div className="qty-mini">
                    <button onClick={()=>onQty(it.id,-1)}><Icon name="minus" size={14}/></button>
                    <span>{it.qty}</span>
                    <button onClick={()=>onQty(it.id,1)}><Icon name="plus" size={14}/></button>
                  </div>
                  <span className="lp">{tk(it.price*it.qty)}</span>
                </div>
                <button className="cart-rm" onClick={()=>onRemove(it.id)}>Remove</button>
              </div>
            </div>
          ))}
        </div>

        {items.length > 0 && (
          <div className="drawer-foot">
            <div className="free-ship-note">
              <Icon name={freeShip?"check":"truck"} size={15} />
              {freeShip ? "You qualify for free delivery" : `Add ${tk(FREE_SHIP_THRESHOLD-subtotal)} for free delivery`}
            </div>
            <div className="sum-row"><span>Subtotal</span><span>{tk(subtotal)}</span></div>
            <div className="sum-row"><span>Delivery</span><span>{freeShip ? "Free" : tk(60)}</span></div>
            <div className="sum-row total"><span>Total</span><span>{tk(subtotal + (freeShip?0:60))}</span></div>
            <button className="btn btn-primary btn-block btn-lg" onClick={()=>{onClose(); onCheckout();}}>
              Checkout <Icon name="arrowR" size={18} />
            </button>
          </div>
        )}
      </aside>
    </React.Fragment>
  );
}

Object.assign(window, { Header, ProductCard, CartDrawer, Logo, BrandMark, CardBadges, discountPct, FREE_GIFT_THRESHOLD, FREE_SHIP_THRESHOLD });
