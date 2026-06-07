/* Shuvo — Shop listing (working filters) + Product detail (Babel/JSX) */

function Checkbox({ on }) {
  return <span className={"checkbox"+(on?" on":"")}><Icon name="check" size={12} sw={2.4}/></span>;
}

function Shop({ nav, query, onNav, onAdd, onOpen, wish, onWish }) {
  const cats = window.SHUVO.categories;
  const all = window.SHUVO.products;

  const [selCats, setSelCats] = useState(nav.cat && nav.cat!=="all" ? [nav.cat] : []);
  const [selBadge, setSelBadge] = useState(nav.deal ? ["deal"] : []);
  const [maxPrice, setMaxPrice] = useState(2700);
  const [sort, setSort] = useState("pop");
  const [filterOpen, setFilterOpen] = useState(false);

  useEffect(()=>{
    setSelCats(nav.cat && nav.cat!=="all" ? [nav.cat] : []);
    setSelBadge(nav.deal ? ["deal"] : []);
  }, [nav.cat, nav.deal, nav.key]);

  const toggle = (arr, set, v) => set(arr.includes(v) ? arr.filter(x=>x!==v) : [...arr, v]);

  const counts = {};
  all.forEach(p => counts[p.cat]=(counts[p.cat]||0)+1);

  let list = all.filter(p => {
    if (selCats.length && !selCats.includes(p.cat)) return false;
    if (p.price > maxPrice) return false;
    if (selBadge.includes("deal") && !p.oldPrice) return false;
    if (selBadge.includes("best") && p.badge!=="best") return false;
    if (selBadge.includes("new") && p.badge!=="new") return false;
    if (query && nav.search) {
      const q = query.toLowerCase();
      if (!p.name.toLowerCase().includes(q) && !p.cat.includes(q)) return false;
    }
    return true;
  });

  list = [...list].sort((a,b)=>{
    if (sort==="low") return a.price-b.price;
    if (sort==="high") return b.price-a.price;
    if (sort==="rate") return b.rating-a.rating;
    return (b.badge==="best"?1:0)-(a.badge==="best"?1:0) || b.reviews-a.reviews;
  });

  const activeCat = selCats.length===1 ? cats.find(c=>c.id===selCats[0]) : null;
  const title = nav.search && query ? `Results for "${query}"` : activeCat ? activeCat.name : selBadge.includes("deal") ? "Offer Zone" : "All Products";

  const badges = [["deal","On Offer"],["best","Best Sellers"],["new","New Arrivals"]];

  const clearAll = () => { setSelCats([]); setSelBadge([]); setMaxPrice(2700); };
  const hasFilters = selCats.length || selBadge.length || maxPrice<2700;

  return (
    <div>
      <div className="page-head">
        <div className="wrap">
          <div className="crumbs">
            <a href="#" onClick={(e)=>{e.preventDefault();onNav({page:"home"});}}>Home</a>
            <Icon name="chevR" size={13}/> <span>{title}</span>
          </div>
          <h1>{title}</h1>
          <p className="sub">{activeCat ? activeCat.note : "Pure, organic and honestly sourced — pick your aisle."}</p>
        </div>
      </div>

      <div className="wrap">
        <div className="shop-layout">
          {/* FILTERS */}
          <aside className={"filters app-scroll"+(filterOpen?" on":"")}>
            <div className="filter-group" style={{display:"flex",alignItems:"center",justifyContent:"space-between"}}>
              <h5 style={{margin:0}}>Filters</h5>
              {hasFilters ? <button className="cart-rm" style={{color:"var(--green)"}} onClick={clearAll}>Clear all</button> : null}
              <button className="x icon-btn" style={{display:filterOpen?"grid":"none"}} onClick={()=>setFilterOpen(false)}><Icon name="close" size={20}/></button>
            </div>
            <div className="filter-group">
              <h5>Category</h5>
              {cats.map(c=>(
                <div className="fopt" key={c.id} onClick={()=>toggle(selCats,setSelCats,c.id)}>
                  <Checkbox on={selCats.includes(c.id)}/> {c.name}
                  <span className="fcount">{counts[c.id]||0}</span>
                </div>
              ))}
            </div>
            <div className="filter-group">
              <h5>Highlights</h5>
              <div className="chip-row">
                {badges.map(([k,label])=>(
                  <button key={k} className={"chip"+(selBadge.includes(k)?" on":"")} onClick={()=>toggle(selBadge,setSelBadge,k)}>{label}</button>
                ))}
              </div>
            </div>
            <div className="filter-group">
              <h5>Max price <span style={{color:"var(--green)",fontWeight:800}}>{tk(maxPrice)}</span></h5>
              <input type="range" min="200" max="2700" step="50" value={maxPrice}
                onChange={(e)=>setMaxPrice(+e.target.value)} style={{width:"100%",accentColor:"var(--green)"}}/>
              <div style={{display:"flex",justifyContent:"space-between",fontSize:12,color:"var(--muted)",marginTop:4}}>
                <span>৳200</span><span>৳2,700+</span>
              </div>
            </div>
          </aside>

          {/* RESULTS */}
          <div>
            <div className="shop-toolbar">
              <div style={{display:"flex",alignItems:"center",gap:12}}>
                <button className="btn btn-ghost filter-toggle" onClick={()=>setFilterOpen(true)}><Icon name="filter" size={16}/> Filters</button>
                <span className="result-count"><b>{list.length}</b> products</span>
              </div>
              <div className="select">
                <Icon name="sort" size={16}/>
                <select value={sort} onChange={(e)=>setSort(e.target.value)}>
                  <option value="pop">Most popular</option>
                  <option value="low">Price: low to high</option>
                  <option value="high">Price: high to low</option>
                  <option value="rate">Top rated</option>
                </select>
              </div>
            </div>

            {hasFilters ? (
              <div className="active-filters">
                {selCats.map(c=>(
                  <span className="fpill" key={c}>{cats.find(x=>x.id===c).name}
                    <button onClick={()=>toggle(selCats,setSelCats,c)}><Icon name="x" size={11}/></button></span>
                ))}
                {selBadge.map(b=>(
                  <span className="fpill" key={b}>{badges.find(x=>x[0]===b)[1]}
                    <button onClick={()=>toggle(selBadge,setSelBadge,b)}><Icon name="x" size={11}/></button></span>
                ))}
                {maxPrice<2700 && <span className="fpill">Under {tk(maxPrice)}
                  <button onClick={()=>setMaxPrice(2700)}><Icon name="x" size={11}/></button></span>}
              </div>
            ) : null}

            {list.length ? (
              <div className="grid-4">
                {list.map(p=>(
                  <ProductCard key={p.id} p={p} onAdd={onAdd} onOpen={onOpen}
                    wished={wish.includes(p.id)} onWish={onWish}/>
                ))}
              </div>
            ) : (
              <div className="empty-state">
                <Icon name="search" size={50}/>
                <h3>No products match</h3>
                <p>Try widening your filters or clearing the search.</p>
                <button className="btn btn-primary" style={{marginTop:16}} onClick={clearAll}>Clear filters</button>
              </div>
            )}
          </div>
        </div>
      </div>
      <Footer onNav={onNav}/>
    </div>
  );
}

// ---------------- PRODUCT DETAIL ----------------
function ProductDetail({ product, onNav, onAdd, onOpen, wish, onWish }) {
  const [qty, setQty] = useState(1);
  const [thumb, setThumb] = useState(0);
  const [wOpt, setWOpt] = useState(1);
  const cat = window.SHUVO.categories.find(c=>c.id===product.cat);
  const pct = discountPct(product);
  const related = window.SHUVO.products.filter(p=>p.cat===product.cat && p.id!==product.id).slice(0,4);
  const weights = [product.weight, product.weight.includes("kg")?"500 g":"250 g"];

  const add = () => { for(let i=0;i<qty;i++) onAdd(product); };

  return (
    <div>
      <div className="wrap">
        <div className="crumbs" style={{paddingTop:20}}>
          <a href="#" onClick={(e)=>{e.preventDefault();onNav({page:"home"});}}>Home</a>
          <Icon name="chevR" size={13}/>
          <a href="#" onClick={(e)=>{e.preventDefault();onNav({page:"shop",cat:product.cat});}}>{cat.name}</a>
          <Icon name="chevR" size={13}/> <span>{product.name}</span>
        </div>

        <div className="pdp">
          <div className="pdp-gallery">
            <div className="pdp-main-img" style={{"--ph-bg": softBg(catTint(product.cat))}}>
              <div className="ph-inner">
                <div className="ph-jar" style={{width:90,height:104,margin:"0 auto 16px",background:catTint(product.cat)+"44"}}></div>
                <span className="ph-label">{cat.name} · {weights[wOpt-1]||product.weight}<br/>product photo</span>
              </div>
            </div>
            <div className="pdp-thumbs">
              {[0,1,2,3].map(i=>(
                <div key={i} className={"pdp-thumb"+(thumb===i?" on":"")} onClick={()=>setThumb(i)}
                  style={{"--ph-bg": softBg(catTint(product.cat))}}>
                  <div className="ph-jar" style={{width:26,height:30,margin:0,background:catTint(product.cat)+"44"}}></div>
                </div>
              ))}
            </div>
          </div>

          <div className="pdp-info">
            <span className="pcard-cat">{cat.name}</span>
            <CardBadges p={product} />
            <h1>{product.name}</h1>
            <div className="pdp-rate">
              <Stars value={product.rating} size={17}/>
              <b style={{color:"var(--ink)"}}>{product.rating}</b>
              <span>· {product.reviews} reviews</span>
              <span style={{color:"var(--green)",fontWeight:600}}>· In stock</span>
            </div>
            <div className="pdp-price">
              <span className="price"><span className="tk">৳</span>{product.price.toLocaleString()}</span>
              {product.oldPrice && <span className="price-old">{tk(product.oldPrice)}</span>}
            </div>
            {pct>0 && <div className="pdp-save-line">You save {tk(product.oldPrice-product.price)} ({pct}% off)</div>}

            <p className="pdp-blurb">{product.blurb}</p>

            <div>
              <div style={{fontSize:13,fontWeight:700,marginBottom:10,color:"var(--ink-soft)"}}>Choose size</div>
              <div className="pdp-weights">
                {weights.map((w,i)=>(
                  <div key={i} className={"weight-opt"+(wOpt===i+1?" on":"")} onClick={()=>setWOpt(i+1)}>
                    {w}<small>{i===0?tk(product.price):tk(Math.round(product.price*0.55))}</small>
                  </div>
                ))}
              </div>
            </div>

            <div className="pdp-buy">
              <div className="qty">
                <button onClick={()=>setQty(Math.max(1,qty-1))}><Icon name="minus" size={18}/></button>
                <span>{qty}</span>
                <button onClick={()=>setQty(qty+1)}><Icon name="plus" size={18}/></button>
              </div>
              <button className="btn btn-primary btn-lg" style={{flex:1}} onClick={add}><Icon name="cart" size={18}/> Add to cart · {tk(product.price*qty)}</button>
              <button className="icon-btn" style={{border:"1.5px solid var(--line)",borderRadius:14,width:52}} onClick={()=>onWish(product)}>
                <Icon name="heart" size={20} fill={wish.includes(product.id)?"var(--sale)":"none"} stroke={wish.includes(product.id)?"var(--sale)":"currentColor"}/>
              </button>
            </div>

            <div className="pdp-trust">
              {[["leaf","100% Organic","Lab-tested purity"],["truck","Fast delivery","24–72 hours"],["shield","Secure payment","COD available"],["refresh","Happy return","Easy 7-day return"]].map((t,i)=>(
                <div className="pdp-trust-item" key={i}>
                  <Icon name={t[0]} size={22}/>
                  <span><b>{t[1]}</b><span>{t[2]}</span></span>
                </div>
              ))}
            </div>
          </div>
        </div>

        <div className="section" style={{paddingBottom:20}}>
          <SectionHead eyebrow="You may also like" title={"More "+cat.name}/>
          <div className="grid-4">
            {related.map(p=>(
              <ProductCard key={p.id} p={p} onAdd={onAdd} onOpen={onOpen}
                wished={wish.includes(p.id)} onWish={onWish}/>
            ))}
          </div>
        </div>
      </div>
      <Footer onNav={onNav}/>
    </div>
  );
}

Object.assign(window, { Shop, ProductDetail });
