/* Shuvo — Checkout + order success (Babel/JSX) */

function Checkout({ items, onNav, onQty, onRemove, onPlaceOrder }) {
  const [pay, setPay] = useState("cod");
  const [placed, setPlaced] = useState(false);
  const [orderNo] = useState("SHV-" + Math.floor(100000 + Math.random()*900000));
  const [form, setForm] = useState({ name:"", phone:"", address:"", city:"Dhaka", note:"" });
  const set = (k,v)=> setForm(f=>({...f,[k]:v}));

  const subtotal = items.reduce((s,i)=>s+i.price*i.qty,0);
  const freeShip = subtotal >= FREE_SHIP_THRESHOLD;
  const ship = freeShip ? 0 : 60;
  const discount = pay==="online" ? Math.round(subtotal*0.02) : 0;
  const total = subtotal + ship - discount;

  const valid = form.name.trim() && form.phone.trim().length>=6 && form.address.trim();

  const place = () => {
    if (!valid) return;
    setPlaced(true);
    onPlaceOrder();
    window.scrollTo({top:0});
  };

  if (placed) {
    return (
      <div>
        <div className="wrap">
          <div className="success">
            <div className="success-ico"><Icon name="check" size={48} sw={2.4}/></div>
            <h1>Order confirmed!</h1>
            <p>Thank you, {form.name.split(" ")[0] || "friend"} — your organic goodies are on the way.</p>
            <span className="ord-no">Order #{orderNo}</span>
            <p style={{fontSize:14}}>A confirmation has been sent. {pay==="cod" ? "Please keep "+tk(total)+" ready for cash on delivery." : "Payment received."}</p>
            <div style={{display:"flex",gap:12,justifyContent:"center",marginTop:24,flexWrap:"wrap"}}>
              <button className="btn btn-primary btn-lg" onClick={()=>onNav({page:"home"})}>Continue shopping</button>
              <button className="btn btn-ghost btn-lg" onClick={()=>onNav({page:"shop"})}>Track order</button>
            </div>
          </div>
        </div>
        <Footer onNav={onNav}/>
      </div>
    );
  }

  if (!items.length) {
    return (
      <div>
        <div className="wrap">
          <div className="success">
            <div className="cart-empty-ico" style={{width:96,height:96}}><Icon name="bag" size={42}/></div>
            <h1>Your cart is empty</h1>
            <p>Add a few organic essentials before checking out.</p>
            <button className="btn btn-primary btn-lg" style={{marginTop:20}} onClick={()=>onNav({page:"shop"})}>Browse products</button>
          </div>
        </div>
        <Footer onNav={onNav}/>
      </div>
    );
  }

  return (
    <div>
      <div className="page-head">
        <div className="wrap">
          <div className="crumbs">
            <a href="#" onClick={(e)=>{e.preventDefault();onNav({page:"home"});}}>Home</a>
            <Icon name="chevR" size={13}/> <span>Checkout</span>
          </div>
          <h1>Checkout</h1>
        </div>
      </div>

      <div className="wrap">
        <div className="checkout">
          <div>
            <div className="co-steps">
              <div className="co-step on"><span className="n">1</span> Cart</div>
              <div className="bar"></div>
              <div className="co-step on"><span className="n">2</span> Details</div>
              <div className="bar"></div>
              <div className="co-step"><span className="n">3</span> Done</div>
            </div>

            <div className="co-card">
              <h3><Icon name="pin" size={20}/> Delivery details</h3>
              <div className="field-row">
                <div className="field">
                  <label>Full name *</label>
                  <input value={form.name} onChange={e=>set("name",e.target.value)} placeholder="e.g. Rahim Ahmed"/>
                </div>
                <div className="field">
                  <label>Phone number *</label>
                  <input value={form.phone} onChange={e=>set("phone",e.target.value)} placeholder="01XXXXXXXXX"/>
                </div>
              </div>
              <div className="field">
                <label>Full address *</label>
                <textarea rows="2" value={form.address} onChange={e=>set("address",e.target.value)} placeholder="House, road, area"></textarea>
              </div>
              <div className="field-row">
                <div className="field">
                  <label>City / District</label>
                  <input value={form.city} onChange={e=>set("city",e.target.value)}/>
                </div>
                <div className="field">
                  <label>Note (optional)</label>
                  <input value={form.note} onChange={e=>set("note",e.target.value)} placeholder="Landmark, delivery time…"/>
                </div>
              </div>
            </div>

            <div className="co-card">
              <h3><Icon name="shield" size={20}/> Payment method</h3>
              {[
                ["cod","Cash on Delivery","Pay when it arrives at your door","COD"],
                ["online","Online Payment","bKash · Nagad · Card — extra 2% off","−2%"],
                ["card","Card on Delivery","Pay by card at delivery","POS"],
              ].map(([k,t,s,logo])=>(
                <label className={"pay-opt"+(pay===k?" on":"")} key={k} onClick={()=>setPay(k)}>
                  <span className="radio"></span>
                  <span><b>{t}</b><span>{s}</span></span>
                  <span className="pay-logo" style={k==="online"?{color:"var(--green)"}:{}}>{logo}</span>
                </label>
              ))}
            </div>
          </div>

          {/* SUMMARY */}
          <div className="co-summary">
            <div className="co-summary-head">Order summary · {items.reduce((s,i)=>s+i.qty,0)} items</div>
            <div className="co-summary-body app-scroll">
              {items.map(it=>(
                <div className="cart-line" key={it.id} style={{padding:"14px 0"}}>
                  <div className="cart-line-art" style={{"--ph-bg": softBg(catTint(it.cat)),width:56,height:56}}>
                    <div className="ph-jar" style={{width:26,height:30,margin:0,background:catTint(it.cat)+"44"}}></div>
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
                  </div>
                </div>
              ))}
            </div>
            <div className="co-summary-foot">
              <div className="co-promo">
                <input placeholder="Promo code"/>
                <button className="btn btn-ghost">Apply</button>
              </div>
              <div className="sum-row"><span>Subtotal</span><span>{tk(subtotal)}</span></div>
              <div className="sum-row"><span>Delivery</span><span>{freeShip?"Free":tk(ship)}</span></div>
              {discount>0 && <div className="sum-row" style={{color:"var(--green)"}}><span>Online discount</span><span>−{tk(discount)}</span></div>}
              <div className="sum-row total"><span>Total</span><span>{tk(total)}</span></div>
              <button className={"btn btn-primary btn-block btn-lg"} style={{opacity:valid?1:.55}} onClick={place}>
                {valid ? <span>Place order · {tk(total)}</span> : "Fill details to continue"}
              </button>
              <div className="free-ship-note" style={{justifyContent:"center",marginTop:12,marginBottom:0}}>
                <Icon name="shield" size={15}/> Secure checkout · COD available
              </div>
            </div>
          </div>
        </div>
      </div>
      <Footer onNav={onNav}/>
    </div>
  );
}

Object.assign(window, { Checkout });
