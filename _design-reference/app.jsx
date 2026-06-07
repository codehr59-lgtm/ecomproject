/* Shuvo — main app: routing, cart + wishlist state (Babel/JSX) */

function App() {
  const [route, setRoute] = useState({ page:"home", key:0 });
  const [cart, setCart] = useState([]);
  const [wish, setWish] = useState([]);
  const [cartOpen, setCartOpen] = useState(false);
  const [dir, setDir] = useState("A");
  const [query, setQuery] = useState("");
  const [toast, setToast] = useState(null);
  const toastTimer = useRef(null);
  const scrollRef = useRef(null);

  const showToast = (msg) => {
    setToast(msg);
    clearTimeout(toastTimer.current);
    toastTimer.current = setTimeout(()=>setToast(null), 1900);
  };

  const nav = (r) => {
    setRoute({ ...r, key: (route.key||0)+1 });
    setCartOpen(false);
    if (scrollRef.current) scrollRef.current.scrollTo({top:0});
    window.scrollTo({top:0});
  };

  const openProduct = (p) => nav({ page:"product", product:p });

  const addToCart = (p) => {
    setCart(c => {
      const ex = c.find(i=>i.id===p.id);
      if (ex) return c.map(i=>i.id===p.id?{...i,qty:i.qty+1}:i);
      return [...c, { id:p.id, name:p.name, weight:p.weight, price:p.price, cat:p.cat, qty:1 }];
    });
    showToast(p.name + " added to cart");
  };
  const changeQty = (id,d) => setCart(c => c.map(i=>i.id===id?{...i,qty:Math.max(1,i.qty+d)}:i));
  const removeItem = (id) => setCart(c => c.filter(i=>i.id!==id));
  const toggleWish = (p) => setWish(w => w.includes(p.id) ? w.filter(x=>x!==p.id) : [...w, p.id]);
  const buyNow = (p) => { addToCart(p); nav({page:"checkout"}); };

  const cartCount = cart.reduce((s,i)=>s+i.qty,0);

  // keep selected product fresh
  const product = route.product;

  return (
    <div className="stage">
      <div style={{width:"100%"}}>
        <Header
          cartCount={cartCount} wishCount={wish.length}
          onCartOpen={()=>setCartOpen(true)} onNav={nav}
          query={query} setQuery={setQuery}
          activeCat={route.page==="shop" ? route.cat : (route.page==="product" && product ? product.cat : null)}
        />

        <main ref={scrollRef}>
          {route.page==="home" &&
            <Home onNav={nav} onAdd={addToCart} onBuy={buyNow}
              onOpen={openProduct} wish={wish} onWish={toggleWish}/>}
          {route.page==="shop" &&
            <Shop nav={route} query={query} onNav={nav} onAdd={addToCart}
              onOpen={openProduct} wish={wish} onWish={toggleWish}/>}
          {route.page==="product" && product &&
            <ProductDetail product={product} onNav={nav} onAdd={addToCart}
              onOpen={openProduct} wish={wish} onWish={toggleWish}/>}
          {route.page==="checkout" &&
            <Checkout items={cart} onNav={nav} onQty={changeQty} onRemove={removeItem}
              onPlaceOrder={()=>setCart([])}/>}
        </main>

        <CartDrawer
          open={cartOpen} items={cart} onClose={()=>setCartOpen(false)}
          onQty={changeQty} onRemove={removeItem} onNav={nav}
          onCheckout={()=>nav({page:"checkout"})}
        />

        <div className={"toast"+(toast?" on":"")}>
          <Icon name="check" size={18}/> {toast}
        </div>
      </div>
    </div>
  );
}

ReactDOM.createRoot(document.getElementById("root")).render(<App/>);
