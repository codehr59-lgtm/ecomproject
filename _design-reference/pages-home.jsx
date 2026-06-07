/* Shuvo — Home page rebuilt to match reference storefront layout (Babel/JSX) */

function SectionHead({ eyebrow, title, sub, action, onAction }) {
  return (
    <div className="section-head">
      <div>
        {eyebrow && <span className="eyebrow">{eyebrow}</span>}
        <h2 style={{marginTop: eyebrow?12:0}}>{title}</h2>
        {sub && <p>{sub}</p>}
      </div>
      {action && <a className="see-all" href="#" onClick={(e)=>{e.preventDefault(); onAction&&onAction();}}>{action} <Icon name="arrowR" size={16}/></a>}
    </div>
  );
}

function RailHead({ title, onView }) {
  return (
    <div className="rail-head">
      <h2>{title}</h2>
      <a className="view-all" href="#" onClick={(e)=>{e.preventDefault(); onView&&onView();}}>
        View all items <Icon name="arrowR" size={15}/>
      </a>
    </div>
  );
}

function Dots({ n, active }) {
  return <div className="dots">{Array.from({length:n}).map((_,i)=><i key={i} className={i===(active||0)?"on":""}/>)}</div>;
}

// ---------------- SPLIT HERO ----------------
function SplitHero({ onNav }) {
  return (
    <div className="wrap home-hero">
      <div className="hero-split">
        <a className="hbanner hbanner-left" href="#" onClick={(e)=>{e.preventDefault();onNav({page:"shop"});}}>
          <div className="hbanner-inner">
            <span className="hb-kicker">Straight from nature</span>
            <h2>Pure food, to your home</h2>
            <button className="btn">Shop the harvest <Icon name="arrowR" size={17}/></button>
          </div>
          <span className="hbanner-tag">hero banner · product lineup</span>
        </a>
        <a className="hbanner hbanner-right" href="#" onClick={(e)=>{e.preventDefault();onNav({page:"shop",cat:"mango"});}}>
          <div className="hbanner-inner">
            <span className="hb-kicker">Season special</span>
            <h2>Naturally sweet mangoes</h2>
            <p>Pre-order fresh from the orchard.</p>
            <button className="btn btn-honey">Reserve now</button>
          </div>
          <span className="hbanner-tag">banner · mango</span>
        </a>
      </div>
      <Dots n={4} active={0}/>
    </div>
  );
}

// ---------------- FEATURED CATEGORIES ----------------
function FeaturedCats({ onNav }) {
  const cats = window.SHUVO.categories;
  return (
    <div className="rail">
      <div className="wrap">
        <div className="center-title"><h2>Featured Categories</h2><div className="u"></div></div>
        <div className="fcat-row">
          {cats.map(c => (
            <a key={c.id} className="fcat" href="#" onClick={(e)=>{e.preventDefault(); onNav({page:"shop", cat:c.id});}}>
              <span className="fcat-img" style={{"--ph-bg": softBg(c.tint)}}>
                <span className="ph-jar" style={{background: c.tint+"55", borderColor:"rgba(255,255,255,.85)"}}></span>
              </span>
              <span className="fcat-name">{c.name}</span>
            </a>
          ))}
        </div>
      </div>
    </div>
  );
}

// ---------------- TOP SELLING (2x2 horizontal) ----------------
function TopSelling({ onNav, onAdd, onOpen, onBuy }) {
  const items = window.SHUVO.products.filter(p=>p.badge==="best").slice(0,4);
  return (
    <div className="rail">
      <div className="wrap">
        <div className="center-title"><h2>Top Selling Products</h2><div className="u"></div></div>
        <div className="top-grid">
          {items.map(p=>{
            const save = p.oldPrice ? p.oldPrice-p.price : 0;
            return (
              <div className="top-card" key={p.id}>
                {p.badge==="best" && <span className="ribbon-best">Best Selling</span>}
                <div className="top-card-img" style={{"--ph-bg": softBg(catTint(p.cat))}} onClick={()=>onOpen(p)}>
                  <div className="ph-jar" style={{width:48,height:56,margin:0,background:catTint(p.cat)+"44"}}></div>
                </div>
                <div className="top-card-info">
                  <h4 onClick={()=>onOpen(p)}>{p.name} <span style={{color:"var(--muted)",fontWeight:600,fontSize:14}}>· {p.weight}</span></h4>
                  <div className="price-row">
                    <span className="price"><span className="tk">৳</span>{p.price.toLocaleString()}</span>
                    {p.oldPrice && <span className="price-old">{tk(p.oldPrice)}</span>}
                    {save>0 && <span className="save-pill">Save {tk(save)}</span>}
                  </div>
                  <div className="top-card-foot">
                    <button className="add-btn" onClick={()=>onAdd(p)}><Icon name="cart" size={16}/> Add To Cart</button>
                    <button className="buy-btn" onClick={()=>onBuy(p)}>Buy now</button>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
}

// ---------------- BRANDS ----------------
function Brands() {
  return (
    <div className="rail">
      <div className="wrap">
        <div className="rail-head"><h2>Our Brands</h2><a className="view-all" href="#" onClick={e=>e.preventDefault()}>See all <Icon name="arrowR" size={15}/></a></div>
        <div className="brands-row">
          {window.SHUVO.brands.map((b,i)=><div className="brand-card" key={i}>{b}</div>)}
        </div>
      </div>
    </div>
  );
}

// ---------------- PRODUCT RAIL (5-up) ----------------
function Rail({ title, products, cat, onNav, onAdd, onOpen, onBuy, wish, onWish, dots }) {
  return (
    <div className="rail">
      <div className="wrap">
        <RailHead title={title} onView={()=>onNav({page:"shop", cat})}/>
        <div className="grid-5">
          {products.map(p => (
            <ProductCard key={p.id} p={p} onAdd={onAdd} onOpen={onOpen} onBuy={onBuy}
              wished={wish.includes(p.id)} onWish={onWish}/>
          ))}
        </div>
        {dots && <Dots n={dots} active={0}/>}
      </div>
    </div>
  );
}

// ---------------- COMBO BAND ----------------
function ComboBand({ onNav }) {
  const combos = [
    { name:"Ghee (Half Kg) & Lychee Honey Sachet Combo", price:1000, old:1140, cat:"oil-ghee", save:"12.3%" },
    { name:"Ghee (1 Kg) & Lychee Honey Sachet Combo", price:1800, old:2040, cat:"oil-ghee", save:"11.8%" },
    { name:"Shahi Masala & Lychee Honey Sachet Combo", price:1500, old:1740, cat:"spices", save:"13.8%" },
    { name:"Kala Bhuna & Lychee Honey Sachet Combo", price:1500, old:1740, cat:"spices", save:"13.8%" },
    { name:"Mustard Oil & Lychee Honey Sachet Combo", price:1600, old:1790, cat:"oil-ghee", save:"10.6%" },
  ];
  return (
    <div className="rail">
      <div className="wrap">
        <div className="combo-band">
          <div className="combo-band-head">
            <span className="cb-ico"><Icon name="gift" size={22}/></span>
            <h2>Exclusive Combo Deals</h2>
            <a className="view-all" href="#" onClick={(e)=>{e.preventDefault();onNav({page:"shop"});}}>View All Combos <Icon name="arrowR" size={15}/></a>
          </div>
          <div className="combo-strip">
            {combos.map((c,i)=>(
              <div className="combo-card" key={i}>
                <div className="ctag">
                  <span className="badge badge-save">Save {c.save}</span>
                  <span className="badge badge-best">Combo</span>
                </div>
                <div className="combo-card-img" style={{"--ph-bg": softBg(catTint(c.cat))}}>
                  <div className="ph-jar" style={{width:34,height:40,margin:0,background:catTint(c.cat)+"44"}}></div>
                </div>
                <h5>{c.name}</h5>
                <div className="price-row">
                  <span className="price" style={{fontSize:15}}><span className="tk">৳</span>{c.price.toLocaleString()}</span>
                  <span className="price-old" style={{fontSize:12}}>{tk(c.old)}</span>
                </div>
                <a className="view-btn" href="#" onClick={e=>e.preventDefault()}>View Details</a>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

function ImageBand({ title, tag }) {
  return (
    <div className="rail">
      <div className="wrap">
        <div className="img-band">
          <h3>{title}</h3>
          <span className="hbanner-tag">{tag}</span>
        </div>
      </div>
    </div>
  );
}

// ---------------- JUST FOR YOU ----------------
function JustForYou({ onNav, onAdd, onOpen, onBuy, wish, onWish }) {
  const [shown, setShown] = useState(10);
  const all = window.SHUVO.products;
  const list = all.slice(0, shown);
  return (
    <div className="rail">
      <div className="wrap">
        <RailHead title="Just For You" onView={()=>onNav({page:"shop"})}/>
        <div className="grid-5">
          {list.map(p => (
            <ProductCard key={p.id} p={p} onAdd={onAdd} onOpen={onOpen} onBuy={onBuy}
              wished={wish.includes(p.id)} onWish={onWish}/>
          ))}
        </div>
        {shown < all.length && (
          <div className="load-more"><button onClick={()=>setShown(s=>s+10)}>Load More</button></div>
        )}
      </div>
    </div>
  );
}

// ---------------- TESTIMONIALS ----------------
function Testimonials() {
  const t = window.SHUVO.testimonials.slice(0,3);
  return (
    <div className="rail" style={{paddingTop:30}}>
      <div className="wrap">
        <div className="grid-3" style={{display:"grid",gridTemplateColumns:"repeat(3,1fr)",gap:16}}>
          {t.map((x,i)=>(
            <div className="tcard" key={i}>
              <Stars value={5} size={16}/>
              <p>"{x.text}"</p>
              <div className="tcard-author">
                <span className="t-avatar">{x.name[0]}</span>
                <span><b>{x.name}</b><span>{x.role}</span></span>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

// ---------------- LIGHT FOOTER ----------------
function Footer({ onNav }) {
  const cats = window.SHUVO.categories;
  const cols = [
    { h:"Information", links:["About us","Contact us","Company Information","Shuvo Stories","Terms & Conditions","Privacy Policy","Careers"] },
    { h:"Support", links:["Support Center","How to Order","Order Tracking","Payment","Shipping","FAQ"] },
    { h:"Consumer Policy", links:["Happy Return","Refund Policy","Exchange","Cancellation","Pre-Order","Extra Discount"] },
  ];
  const pays = ["VISA","Mastercard","bKash","Nagad","Rocket","DBBL","COD"];
  return (
    <footer className="ftr ftr-light">
      <div className="wrap">
        <div className="ftr-top">
          <div className="ftr-brand">
            <Logo onNav={onNav} footer />
            <p>Shuvo is an e-commerce platform dedicated to providing safe, honest and organic food to every home across Bangladesh.</p>
            <div style={{fontSize:13,color:"var(--muted)",lineHeight:1.9}}>
              <div style={{display:"flex",alignItems:"center",gap:7}}><Icon name="pin" size={14}/> Rampura, Dhaka, Bangladesh</div>
              <div style={{display:"flex",alignItems:"center",gap:7}}><Icon name="phone" size={14}/> 09642-XXXXXX</div>
              <div style={{display:"flex",alignItems:"center",gap:7}}><Icon name="mail" size={14}/> hello@shuvo.com</div>
            </div>
            <div className="ftr-social" style={{marginTop:14}}>
              <a href="#"><Icon name="fb" size={18}/></a>
              <a href="#"><Icon name="ig" size={18}/></a>
              <a href="#"><Icon name="mail" size={18}/></a>
            </div>
            <div className="app-badges">
              <span className="app-badge"><Icon name="phone" size={16}/> <span>Google <b>Play</b></span></span>
              <span className="app-badge"><Icon name="phone" size={16}/> <span>App <b>Store</b></span></span>
            </div>
          </div>
          {cols.map((col,i)=>(
            <div key={i}>
              <h4>{col.h}</h4>
              <ul>{col.links.map((l,j)=>(
                <li key={j}><a href="#" onClick={(e)=>{e.preventDefault(); onNav({page:"shop"});}}>{l}</a></li>
              ))}</ul>
            </div>
          ))}
        </div>
        <div className="ftr-bottom">
          <span>© 2026 Shuvo. Pure, organic &amp; halal — delivered with care.</span>
          <div className="pay-row">
            {pays.map((p,i)=><span className="pay-chip" key={i}>{p}</span>)}
          </div>
        </div>
      </div>
    </footer>
  );
}

// ---------------- HOME ----------------
function Home({ onNav, onAdd, onOpen, onBuy, wish, onWish }) {
  const P = window.SHUVO.products;
  const pick = (f, n) => P.filter(f).slice(0, n);
  const railProps = { onNav, onAdd, onOpen, onBuy, wish, onWish };

  const mango   = pick(p=>p.cat==="mango", 5);
  const honey   = pick(p=>p.cat==="honey", 5);
  const dates   = pick(p=>p.cat==="dates", 5);
  const cooking = P.filter(p=>["spices","oil-ghee","rice"].includes(p.cat)).slice(0, 5);
  const certified = pick(p=>p.certified, 5);

  return (
    <div>
      <SplitHero onNav={onNav}/>
      <FeaturedCats onNav={onNav}/>
      <TopSelling onNav={onNav} onAdd={onAdd} onOpen={onOpen} onBuy={onBuy}/>
      <Brands/>
      <Rail title="Mango"            products={mango}   cat="mango"   dots={5} {...railProps}/>
      <Rail title="All Natural Honey" products={honey}  cat="honey"   dots={5} {...railProps}/>
      <ComboBand onNav={onNav}/>
      <Rail title="Premium Dates"    products={dates}   cat="dates"   dots={5} {...railProps}/>
      <ImageBand title="Make every meal a celebration" tag="banner · cooking essentials"/>
      <Rail title="Cooking Essentials" products={cooking} cat="spices" {...railProps}/>
      <Rail title="Organic Certified"  products={certified} cat="honey" {...railProps}/>
      <JustForYou onNav={onNav} onAdd={onAdd} onOpen={onOpen} onBuy={onBuy} wish={wish} onWish={onWish}/>
      <Testimonials/>
      <Footer onNav={onNav}/>
    </div>
  );
}

Object.assign(window, { Home, Footer, SectionHead, RailHead, Rail, SplitHero, FeaturedCats, TopSelling, Brands, ComboBand, ImageBand, JustForYou, Testimonials });
