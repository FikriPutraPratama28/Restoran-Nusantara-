import "./bootstrap";
import Alpine from "alpinejs";
import intersect from "@alpinejs/intersect";
import persist from "@alpinejs/persist";

Alpine.plugin(intersect);
Alpine.plugin(persist);

// =============================================
// CART STORE
// =============================================
Alpine.store("cart", {
    items: Alpine.$persist([]).as("restaurant_cart"),
    isOpen: false,
    get count() {
        return this.items.reduce((s, i) => s + i.qty, 0);
    },
    get subtotal() {
        return this.items.reduce((s, i) => s + i.price * i.qty, 0);
    },
    get total() {
        return this.subtotal - this.discount;
    },
    discount: 0,
    appliedVoucher: null,

    add(item) {
        const ex = this.items.find((i) => i.id === item.id);
        if (ex) {
            ex.qty++;
        } else {
            this.items.push({ ...item, qty: 1 });
        }
        window.dispatchEvent(
            new CustomEvent("toast", {
                detail: {
                    message: `Item ditambahkan: ${item.name}`,
                    type: "success",
                },
            }),
        );
    },
    remove(id) {
        this.items = this.items.filter((i) => i.id !== id);
    },
    updateQty(id, qty) {
        if (qty <= 0) {
            this.remove(id);
            return;
        }
        const item = this.items.find((i) => i.id === id);
        if (item) item.qty = qty;
    },
    clear() {
        this.items = [];
        this.discount = 0;
        this.appliedVoucher = null;
    },
    applyVoucher(code) {
        const v = {
            HEMAT10: { d: 10, t: "pct", l: "Diskon 10%" },
            GRATIS20: { d: 20000, t: "fix", l: "Potongan Rp 20.000" },
            NEWUSER: { d: 15, t: "pct", l: "Diskon 15% New User" },
        }[code.toUpperCase()];
        if (v) {
            this.discount =
                v.t === "pct" ? Math.round((this.subtotal * v.d) / 100) : v.d;
            this.appliedVoucher = { code: code.toUpperCase(), ...v };
            return { success: true, message: `Voucher berhasil: ${v.l}` };
        }
        return { success: false, message: "Kode voucher tidak valid" };
    },
    open() {
        this.isOpen = true;
        document.body.style.overflow = "hidden";
    },
    close() {
        this.isOpen = false;
        document.body.style.overflow = "";
    },
});

// =============================================
// DARK MODE STORE
// =============================================
Alpine.store("theme", {
    dark: Alpine.$persist(false).as("restaurant_dark_mode"),
    toggle() {
        this.dark = !this.dark;
        this.apply();
    },
    apply() {
        document.documentElement.classList.toggle("dark", this.dark);
    },
    init() {
        this.apply();
    },
});

// =============================================
// TOAST NOTIFICATION (upgrade dari sebelumnya)
// =============================================
Alpine.data("toastManager", () => ({
    toasts: [],
    counter: 0,
    init() {
        window.addEventListener("toast", (e) => {
            const id = ++this.counter;
            this.toasts.push({
                id,
                message: e.detail.message,
                type: e.detail.type || "success",
            });
            setTimeout(() => {
                this.toasts = this.toasts.filter((t) => t.id !== id);
            }, 3500);
        });
        // backward compat
        window.addEventListener("cart-notification", (e) => {
            window.dispatchEvent(
                new CustomEvent("toast", {
                    detail: {
                        message: `Keranjang: ${e.detail.message}`,
                        type: "success",
                    },
                }),
            );
        });
    },
    remove(id) {
        this.toasts = this.toasts.filter((t) => t.id !== id);
    },
}));

// =============================================
// MENU DATA (shared, dipakai di beberapa komponen)
// =============================================
const ALL_MENUS = [
    {
        id: 1,
        name: "Nasi Goreng Spesial",
        category: "makanan",
        price: 35000,
        originalPrice: 45000,
        image: "https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=400&h=300&fit=crop",
        rating: 4.8,
        reviews: 234,
        label: "best-seller",
        description:
            "Nasi goreng dengan bumbu rahasia, telur mata sapi, dan ayam crispy",
        isNew: false,
        isPromo: true,
    },
    {
        id: 2,
        name: "Ayam Bakar Madu",
        category: "makanan",
        price: 45000,
        originalPrice: null,
        image: "https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?w=400&h=300&fit=crop",
        rating: 4.9,
        reviews: 189,
        label: "best-seller",
        description: "Ayam bakar dengan saus madu spesial dan lalapan segar",
        isNew: false,
        isPromo: false,
    },
    {
        id: 3,
        name: "Mie Goreng Seafood",
        category: "makanan",
        price: 40000,
        originalPrice: null,
        image: "https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=400&h=300&fit=crop",
        rating: 4.7,
        reviews: 156,
        label: null,
        description: "Mie goreng dengan udang, cumi, dan sayuran segar",
        isNew: true,
        isPromo: false,
    },
    {
        id: 4,
        name: "Sate Ayam Madura",
        category: "makanan",
        price: 30000,
        originalPrice: 38000,
        image: "https://images.unsplash.com/photo-1529563021893-cc83c992d75d?w=400&h=300&fit=crop",
        rating: 4.6,
        reviews: 312,
        label: "popular",
        description: "10 tusuk sate ayam dengan bumbu kacang khas Madura",
        isNew: false,
        isPromo: true,
    },
    {
        id: 5,
        name: "Es Teh Manis",
        category: "minuman",
        price: 8000,
        originalPrice: null,
        image: "https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&h=300&fit=crop",
        rating: 4.5,
        reviews: 445,
        label: "popular",
        description: "Teh manis segar dengan es batu pilihan",
        isNew: false,
        isPromo: false,
    },
    {
        id: 6,
        name: "Jus Alpukat",
        category: "minuman",
        price: 18000,
        originalPrice: null,
        image: "https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?w=400&h=300&fit=crop",
        rating: 4.8,
        reviews: 198,
        label: "best-seller",
        description: "Jus alpukat segar dengan susu kental manis",
        isNew: false,
        isPromo: false,
    },
    {
        id: 7,
        name: "Kopi Susu Kekinian",
        category: "minuman",
        price: 22000,
        originalPrice: 28000,
        image: "https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400&h=300&fit=crop",
        rating: 4.9,
        reviews: 567,
        label: "best-seller",
        description: "Kopi susu dengan espresso shot dan susu segar",
        isNew: false,
        isPromo: true,
    },
    {
        id: 8,
        name: "Matcha Latte",
        category: "minuman",
        price: 25000,
        originalPrice: null,
        image: "https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=400&h=300&fit=crop",
        rating: 4.7,
        reviews: 123,
        label: null,
        description: "Matcha premium dengan susu oat dan foam lembut",
        isNew: true,
        isPromo: false,
    },
    {
        id: 9,
        name: "Cheesecake Strawberry",
        category: "dessert",
        price: 32000,
        originalPrice: null,
        image: "https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=400&h=300&fit=crop",
        rating: 4.8,
        reviews: 89,
        label: "new",
        description: "Cheesecake lembut dengan topping strawberry segar",
        isNew: true,
        isPromo: false,
    },
    {
        id: 10,
        name: "Brownies Coklat",
        category: "dessert",
        price: 25000,
        originalPrice: 30000,
        image: "https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=400&h=300&fit=crop",
        rating: 4.6,
        reviews: 145,
        label: "popular",
        description: "Brownies coklat premium dengan topping almond",
        isNew: false,
        isPromo: true,
    },
    {
        id: 11,
        name: "Es Krim Gelato",
        category: "dessert",
        price: 28000,
        originalPrice: null,
        image: "https://images.unsplash.com/photo-1497034825429-c343d7c6a68f?w=400&h=300&fit=crop",
        rating: 4.7,
        reviews: 201,
        label: "best-seller",
        description: "Gelato Italia asli dengan berbagai pilihan rasa",
        isNew: false,
        isPromo: false,
    },
    {
        id: 12,
        name: "French Fries",
        category: "snack",
        price: 20000,
        originalPrice: null,
        image: "https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=400&h=300&fit=crop",
        rating: 4.5,
        reviews: 334,
        label: "popular",
        description: "Kentang goreng crispy dengan saus pilihan",
        isNew: false,
        isPromo: false,
    },
    {
        id: 13,
        name: "Onion Ring",
        category: "snack",
        price: 22000,
        originalPrice: 27000,
        image: "https://images.unsplash.com/photo-1639024471283-03518883512d?w=400&h=300&fit=crop",
        rating: 4.4,
        reviews: 167,
        label: null,
        description: "Bawang bombay goreng crispy dengan saus ranch",
        isNew: false,
        isPromo: true,
    },
    {
        id: 14,
        name: "Chicken Wings",
        category: "snack",
        price: 35000,
        originalPrice: null,
        image: "https://images.unsplash.com/photo-1527477396000-e27163b481c2?w=400&h=300&fit=crop",
        rating: 4.8,
        reviews: 278,
        label: "best-seller",
        description: "6 pcs chicken wings dengan saus BBQ pedas",
        isNew: false,
        isPromo: false,
    },
    {
        id: 15,
        name: "Gado-Gado",
        category: "makanan",
        price: 28000,
        originalPrice: null,
        image: "https://images.unsplash.com/photo-1512058564366-18510be2db19?w=400&h=300&fit=crop",
        rating: 4.6,
        reviews: 143,
        label: null,
        description: "Gado-gado dengan bumbu kacang khas dan kerupuk",
        isNew: false,
        isPromo: false,
    },
    {
        id: 16,
        name: "Smoothie Bowl",
        category: "dessert",
        price: 38000,
        originalPrice: null,
        image: "https://images.unsplash.com/photo-1490323914169-4b57d0054c5a?w=400&h=300&fit=crop",
        rating: 4.9,
        reviews: 76,
        label: "new",
        description: "Smoothie bowl dengan granola, buah segar, dan madu",
        isNew: true,
        isPromo: false,
    },
    // Paket
    {
        id: 17,
        name: "Paket Hemat A",
        category: "paket",
        price: 55000,
        originalPrice: 75000,
        image: "https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=300&fit=crop",
        rating: 4.8,
        reviews: 312,
        label: "best-seller",
        description: "Nasi Goreng + Ayam Goreng + Es Teh Manis",
        isNew: false,
        isPromo: true,
    },
    {
        id: 18,
        name: "Paket Keluarga",
        category: "paket",
        price: 150000,
        originalPrice: 200000,
        image: "https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&h=300&fit=crop",
        rating: 4.9,
        reviews: 189,
        label: "best-seller",
        description: "4 Nasi + 4 Lauk pilihan + 4 Minuman + Dessert",
        isNew: false,
        isPromo: true,
    },
    {
        id: 19,
        name: "Paket Romantis",
        category: "paket",
        price: 120000,
        originalPrice: 160000,
        image: "https://images.unsplash.com/photo-1559339352-11d035aa65de?w=400&h=300&fit=crop",
        rating: 4.7,
        reviews: 98,
        label: "popular",
        description: "2 Steak + 2 Minuman + Dessert + Candle Light",
        isNew: true,
        isPromo: true,
    },
    {
        id: 20,
        name: "Paket Sarapan",
        category: "paket",
        price: 35000,
        originalPrice: 48000,
        image: "https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=400&h=300&fit=crop",
        rating: 4.6,
        reviews: 145,
        label: "popular",
        description: "Nasi Uduk + Lauk + Teh Hangat",
        isNew: false,
        isPromo: true,
    },
    {
        id: 21,
        name: "Paket Bisnis",
        category: "paket",
        price: 45000,
        originalPrice: 60000,
        image: "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop",
        rating: 4.5,
        reviews: 234,
        label: null,
        description: "Nasi + Lauk + Sayur + Minuman (cocok untuk makan siang)",
        isNew: false,
        isPromo: true,
    },
    // Seafood
    {
        id: 22, name: "Udang Bakar Madu", category: "seafood",
        price: 65000, originalPrice: null,
        image: "https://images.unsplash.com/photo-1625943553852-781c6dd46faa?w=400&h=300&fit=crop",
        rating: 4.9, reviews: 178, label: "best-seller",
        description: "Udang segar bakar dengan saus madu spesial dan lalapan",
        isNew: false, isPromo: false,
    },
    {
        id: 23, name: "Ikan Gurame Goreng", category: "seafood",
        price: 55000, originalPrice: null,
        image: "https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?w=400&h=300&fit=crop",
        rating: 4.8, reviews: 245, label: "best-seller",
        description: "Gurame segar goreng krispi dengan sambal terasi dan lalapan",
        isNew: false, isPromo: false,
    },
    {
        id: 24, name: "Cumi Saus Tiram", category: "seafood",
        price: 48000, originalPrice: 58000,
        image: "https://images.unsplash.com/photo-1565680018434-b51d7e6e8305?w=400&h=300&fit=crop",
        rating: 4.7, reviews: 134, label: "popular",
        description: "Cumi segar dimasak dengan saus tiram dan paprika",
        isNew: false, isPromo: true,
    },
    {
        id: 25, name: "Lobster Thermidor", category: "seafood",
        price: 150000, originalPrice: 180000,
        image: "https://images.unsplash.com/photo-1553621042-f6e147245754?w=400&h=300&fit=crop",
        rating: 4.9, reviews: 67, label: null,
        description: "Lobster premium dengan saus thermidor keju dan herbs",
        isNew: false, isPromo: true,
    },
    // Aneka Snack
    {
        id: 26, name: "Pisang Goreng Keju", category: "aneka-snack",
        price: 15000, originalPrice: null,
        image: "https://images.unsplash.com/photo-1600326145359-3a44909d1a39?w=400&h=300&fit=crop",
        rating: 4.6, reviews: 289, label: "popular",
        description: "Pisang goreng crispy dengan topping keju dan susu kental manis",
        isNew: false, isPromo: false,
    },
    {
        id: 27, name: "Risoles Mayo", category: "aneka-snack",
        price: 8000, originalPrice: null,
        image: "https://images.unsplash.com/photo-1519864600265-abb23847a447?w=400&h=300&fit=crop",
        rating: 4.7, reviews: 456, label: "best-seller",
        description: "Risoles isi ragout sayuran dan mayonaise, goreng keemasan",
        isNew: false, isPromo: false,
    },
    {
        id: 28, name: "Dimsum Ayam Udang", category: "aneka-snack",
        price: 25000, originalPrice: 30000,
        image: "https://images.unsplash.com/photo-1496116218417-1a781b1c416c?w=400&h=300&fit=crop",
        rating: 4.8, reviews: 267, label: "best-seller",
        description: "Dimsum kukus isi campuran ayam dan udang, 5 pcs",
        isNew: false, isPromo: true,
    },
    // Aneka Sayur
    {
        id: 29, name: "Sayur Asem", category: "aneka-sayur",
        price: 15000, originalPrice: null,
        image: "https://images.unsplash.com/photo-1547592180-85f173990554?w=400&h=300&fit=crop",
        rating: 4.6, reviews: 234, label: "popular",
        description: "Sayur asem segar dengan jagung, kacang panjang, dan labu siam",
        isNew: false, isPromo: false,
    },
    {
        id: 30, name: "Cap Cay Goreng", category: "aneka-sayur",
        price: 22000, originalPrice: null,
        image: "https://images.unsplash.com/photo-1512058564366-18510be2db19?w=400&h=300&fit=crop",
        rating: 4.7, reviews: 189, label: "best-seller",
        description: "Cap cay goreng dengan 10 macam sayuran segar dan saus tiram",
        isNew: false, isPromo: false,
    },
    {
        id: 31, name: "Plecing Kangkung", category: "aneka-sayur",
        price: 12000, originalPrice: null,
        image: "https://images.unsplash.com/photo-1540420773420-3366772f4999?w=400&h=300&fit=crop",
        rating: 4.7, reviews: 201, label: "new",
        description: "Kangkung segar dengan sambal plecing pedas khas Lombok",
        isNew: true, isPromo: false,
    },
    // Nasi Kotak
    {
        id: 32, name: "Nasi Kotak Ayam Goreng", category: "nasi-kotak",
        price: 35000, originalPrice: null,
        image: "https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=400&h=300&fit=crop",
        rating: 4.7, reviews: 312, label: "best-seller",
        description: "Nasi putih + ayam goreng + sayur + sambal + kerupuk, dalam kotak",
        isNew: false, isPromo: false,
    },
    {
        id: 33, name: "Nasi Kotak Rendang", category: "nasi-kotak",
        price: 40000, originalPrice: null,
        image: "https://images.unsplash.com/photo-1596797038530-2c107229654b?w=400&h=300&fit=crop",
        rating: 4.9, reviews: 267, label: "best-seller",
        description: "Nasi putih + rendang sapi + telur balado + sayur, dalam kotak",
        isNew: false, isPromo: false,
    },
    {
        id: 34, name: "Nasi Kotak Spesial", category: "nasi-kotak",
        price: 50000, originalPrice: 60000,
        image: "https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=300&fit=crop",
        rating: 4.9, reviews: 145, label: "new",
        description: "Nasi + ayam + ikan + udang + sayur + dessert + minuman, paket lengkap",
        isNew: true, isPromo: true,
    },
    // Acara Khusus
    {
        id: 35, name: "Paket Ulang Tahun", category: "acara-khusus",
        price: 500000, originalPrice: 650000,
        image: "https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=400&h=300&fit=crop",
        rating: 4.9, reviews: 78, label: "popular",
        description: "Paket untuk 20 orang: nasi, lauk, kue ulang tahun, dekorasi sederhana",
        isNew: false, isPromo: true,
    },
    {
        id: 36, name: "Paket Lamaran", category: "acara-khusus",
        price: 1500000, originalPrice: null,
        image: "https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=400&h=300&fit=crop",
        rating: 4.9, reviews: 34, label: "new",
        description: "Paket eksklusif 50 orang: dekorasi, buffet premium, kue, dan souvenir",
        isNew: true, isPromo: false,
    },
    {
        id: 37, name: "Paket Gathering Kantor", category: "acara-khusus",
        price: 2000000, originalPrice: 2500000,
        image: "https://images.unsplash.com/photo-1555244162-803834f70033?w=400&h=300&fit=crop",
        rating: 4.8, reviews: 45, label: "best-seller",
        description: "Paket 100 orang: buffet lengkap, minuman, snack, dan sound system",
        isNew: false, isPromo: true,
    },
    // Iga
    {
        id: 38, name: "Iga Bakar Madu", category: "iga",
        price: 75000, originalPrice: 90000,
        image: "https://images.unsplash.com/photo-1544025162-d76694265947?w=400&h=300&fit=crop",
        rating: 4.9, reviews: 234, label: "best-seller",
        description: "Iga sapi bakar dengan saus madu spesial, empuk dan juicy",
        isNew: false, isPromo: true,
    },
    {
        id: 39, name: "Iga Penyet Sambal Bawang", category: "iga",
        price: 65000, originalPrice: null,
        image: "https://images.unsplash.com/photo-1529692236671-f1f6cf9683ba?w=400&h=300&fit=crop",
        rating: 4.8, reviews: 189, label: "popular",
        description: "Iga sapi goreng dipenyet dengan sambal bawang pedas dan lalapan",
        isNew: false, isPromo: false,
    },
    {
        id: 40, name: "Iga Bakar Talam", category: "iga",
        price: 85000, originalPrice: null,
        image: "https://images.unsplash.com/photo-1558030006-450675393462?w=400&h=300&fit=crop",
        rating: 4.9, reviews: 156, label: "new",
        description: "Iga sapi bakar disajikan di atas talenan dengan saus BBQ smoky",
        isNew: true, isPromo: false,
    },
    {
        id: 41, name: "Sop Iga Sapi", category: "iga",
        price: 55000, originalPrice: null,
        image: "https://images.unsplash.com/photo-1547592166-23ac45744acd?w=400&h=300&fit=crop",
        rating: 4.7, reviews: 267, label: "popular",
        description: "Sop iga sapi kuah bening dengan wortel, kentang, dan buncis",
        isNew: false, isPromo: false,
    },
    {
        id: 42, name: "Iga Bakar Rica", category: "iga",
        price: 70000, originalPrice: 80000,
        image: "https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=300&fit=crop",
        rating: 4.8, reviews: 198, label: "best-seller",
        description: "Iga bakar dengan bumbu rica-rica pedas khas Manado",
        isNew: false, isPromo: true,
    },
];

// =============================================
// MENU FILTER COMPONENT
// =============================================
Alpine.data("menuFilter", (dbMenus = null) => ({
    search: "",
    activeCategory: "all",
    sortBy: "popular",
    priceMin: 0,
    priceMax: 999999,
    priceRange: "all", // all | under25 | 25to50 | 50to100 | over100
    showOnlyPromo: false,
    showOnlyStock: true,
    selectedMenu: null,
    lastAddedId: null,
    defaultVideo: "https://www.w3schools.com/html/mov_bbb.mp4",
    viewedItems: Alpine.$persist([]).as("viewed_menus"),
    favoriteCategory: Alpine.$persist("makanan").as("fav_category"),

    categories: [
        { id: "all", name: "Semua", icon: "•" },
        { id: "makanan", name: "Makanan", icon: "•" },
        { id: "minuman", name: "Minuman", icon: "•" },
        { id: "dessert", name: "Dessert", icon: "•" },
        { id: "snack", name: "Snack", icon: "•" },
        { id: "paket", name: "Paket Mabar", icon: "•" },
        { id: "seafood", name: "Seafood", icon: "•" },
        { id: "aneka-snack", name: "Aneka Snack", icon: "•" },
        { id: "aneka-sayur", name: "Aneka Sayur", icon: "•" },
        { id: "nasi-kotak", name: "Nasi Kotak", icon: "•" },
        { id: "acara-khusus", name: "Acara Khusus", icon: "•" },
        { id: "iga", name: "Iga", icon: "•" },
    ],

    priceRanges: [
        { id: "all", label: "Semua Harga", min: 0, max: 999999 },
        { id: "under25", label: "< Rp 25.000", min: 0, max: 24999 },
        { id: "25to50", label: "Rp 25–50 rb", min: 25000, max: 50000 },
        { id: "50to100", label: "Rp 50–100 rb", min: 50001, max: 100000 },
        { id: "over100", label: "> Rp 100.000", min: 100001, max: 999999 },
    ],

    quickTags: [
        { label: "Ayam", q: "ayam" },
        { label: "Pedas", q: "pedas" },
        { label: "Minuman", q: "minuman", cat: "minuman" },
        { label: "Nasi", q: "nasi" },
        { label: "Kopi", q: "kopi" },
        { label: "Dessert", q: "dessert", cat: "dessert" },
        { label: "Paket", q: "paket", cat: "paket" },
        { label: "Best", q: "", label_key: "best-seller" },
    ],

    // Pakai data dari DB jika ada, fallback ke ALL_MENUS (hardcoded)
    menus: dbMenus || ALL_MENUS,

    get filtered() {
        let r = this.menus;
        // Filter stok
        if (this.showOnlyStock) r = r.filter((m) => m.isStock !== false);
        // Filter kategori
        if (this.activeCategory !== "all")
            r = r.filter((m) => m.category === this.activeCategory);
        // Filter harga
        const pr =
            this.priceRanges.find((p) => p.id === this.priceRange) ||
            this.priceRanges[0];
        r = r.filter((m) => m.price >= pr.min && m.price <= pr.max);
        // Filter promo
        if (this.showOnlyPromo) r = r.filter((m) => m.isPromo);
        // Search
        if (this.search.trim()) {
            const q = this.search.toLowerCase();
            r = r.filter(
                (m) =>
                    m.name.toLowerCase().includes(q) ||
                    (m.description || "").toLowerCase().includes(q) ||
                    m.category.toLowerCase().includes(q),
            );
        }
        // Sort
        if (this.sortBy === "popular")
            r = [...r].sort((a, b) => b.reviews - a.reviews);
        else if (this.sortBy === "rating")
            r = [...r].sort((a, b) => b.rating - a.rating);
        else if (this.sortBy === "price-low")
            r = [...r].sort((a, b) => a.price - b.price);
        else if (this.sortBy === "price-high")
            r = [...r].sort((a, b) => b.price - a.price);
        else if (this.sortBy === "new")
            r = [...r].sort((a, b) => (b.isNew ? 1 : 0) - (a.isNew ? 1 : 0));
        return r;
    },

    get popularMenus() {
        return [...this.menus]
            .filter(
                (m) =>
                    m.isStock !== false &&
                    (m.label === "best-seller" || m.label === "popular"),
            )
            .sort((a, b) => b.reviews - a.reviews)
            .slice(0, 6);
    },

    get activeFiltersCount() {
        let c = 0;
        if (this.activeCategory !== "all") c++;
        if (this.priceRange !== "all") c++;
        if (this.showOnlyPromo) c++;
        if (this.search.trim()) c++;
        return c;
    },

    applyQuickTag(tag) {
        if (tag.cat) this.activeCategory = tag.cat;
        if (tag.q) this.search = tag.q;
        if (tag.label_key) {
            this.search = "";
            this.sortBy = "popular";
        }
    },

    resetFilters() {
        this.search = "";
        this.activeCategory = "all";
        this.priceRange = "all";
        this.showOnlyPromo = false;
        this.sortBy = "popular";
    },

    // Highlight teks pencarian dalam nama menu
    highlight(text) {
        if (!this.search.trim()) return text;
        const q = this.search.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
        return text.replace(
            new RegExp(`(${q})`, "gi"),
            '<mark class="bg-yellow-200 dark:bg-yellow-800 text-gray-900 dark:text-white rounded px-0.5">$1</mark>',
        );
    },

    // Rekomendasi: berdasarkan kategori favorit & item yang sering dilihat
    get recommendations() {
        const favCat = this.favoriteCategory;
        let recs = this.menus.filter(
            (m) => m.category === favCat && m.label === "best-seller",
        );
        if (recs.length < 4) {
            const extra = this.menus.filter(
                (m) =>
                    m.label === "best-seller" &&
                    !recs.find((r) => r.id === m.id),
            );
            recs = [...recs, ...extra].slice(0, 4);
        }
        return recs.slice(0, 4);
    },

    trackView(menu) {
        this.favoriteCategory = menu.category;
        if (!this.viewedItems.includes(menu.id)) {
            this.viewedItems = [menu.id, ...this.viewedItems].slice(0, 20);
        }
    },

    openDetail(menu) {
        this.selectedMenu = menu;
        this.trackView(menu);
        document.body.style.overflow = "hidden";
    },

    closeDetail() {
        this.selectedMenu = null;
        document.body.style.overflow = "";
    },

    detailVideo(menu) {
        return menu.video || this.defaultVideo;
    },

    formatPrice(price) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(price);
    },

    categoryLabel(slug) {
        const cat = this.categories.find((c) => c.id === slug);
        return cat ? cat.name : slug;
    },

    addToCart(menu) {
        this.trackView(menu);
        this.lastAddedId = menu.id;
        Alpine.store("cart").add({
            id: menu.id,
            name: menu.name,
            price: menu.price,
            image: menu.image,
        });
        setTimeout(() => {
            if (this.lastAddedId === menu.id) {
                this.lastAddedId = null;
            }
        }, 500);
    },
}));

// =============================================
// RESERVATION COMPONENT
// =============================================
Alpine.data("reservation", (dbMenus = []) => ({
    menus: dbMenus.length ? dbMenus : ALL_MENUS,
    step: 1,
    form: {
        date: "",
        time: "",
        guests: 2,
        name: "",
        phone: "",
        email: "",
        notes: "",
        tableArea: "",
        tableNumber: null,
    },
    submitted: false,
    loading: false,
    isAuthenticated:
        document.querySelector("[data-user-authenticated]") !== null,
    message: "",
    messageType: "",
    minDate: new Date().toISOString().split("T")[0],
    timeSlots: [
        "10:00",
        "10:30",
        "11:00",
        "11:30",
        "12:00",
        "12:30",
        "13:00",
        "13:30",
        "14:00",
        "14:30",
        "17:00",
        "17:30",
        "18:00",
        "18:30",
        "19:00",
        "19:30",
        "20:00",
        "20:30",
        "21:00",
    ],

    tables: {
        indoor: [
            {
                id: "I-01",
                label: "Meja 1",
                capacity: 2,
                status: "available",
                position: "Dekat jendela",
            },
            {
                id: "I-02",
                label: "Meja 2",
                capacity: 2,
                status: "available",
                position: "Dekat jendela",
            },
            {
                id: "I-03",
                label: "Meja 3",
                capacity: 4,
                status: "occupied",
                position: "Tengah ruangan",
            },
            {
                id: "I-04",
                label: "Meja 4",
                capacity: 4,
                status: "available",
                position: "Tengah ruangan",
            },
            {
                id: "I-05",
                label: "Meja 5",
                capacity: 4,
                status: "reserved",
                position: "Pojok kiri",
            },
            {
                id: "I-06",
                label: "Meja 6",
                capacity: 6,
                status: "available",
                position: "Pojok kanan",
            },
            {
                id: "I-07",
                label: "Meja 7",
                capacity: 6,
                status: "occupied",
                position: "Dekat bar",
            },
            {
                id: "I-08",
                label: "Meja 8",
                capacity: 8,
                status: "available",
                position: "Ruang VIP",
            },
            {
                id: "I-09",
                label: "Meja 9",
                capacity: 2,
                status: "available",
                position: "Dekat pintu",
            },
            {
                id: "I-10",
                label: "Meja 10",
                capacity: 4,
                status: "reserved",
                position: "Tengah ruangan",
            },
        ],
        outdoor: [
            {
                id: "O-01",
                label: "Meja A",
                capacity: 2,
                status: "available",
                position: "Taman depan",
            },
            {
                id: "O-02",
                label: "Meja B",
                capacity: 2,
                status: "available",
                position: "Taman depan",
            },
            {
                id: "O-03",
                label: "Meja C",
                capacity: 4,
                status: "occupied",
                position: "Dekat kolam",
            },
            {
                id: "O-04",
                label: "Meja D",
                capacity: 4,
                status: "available",
                position: "Dekat kolam",
            },
            {
                id: "O-05",
                label: "Meja E",
                capacity: 4,
                status: "reserved",
                position: "Gazebo 1",
            },
            {
                id: "O-06",
                label: "Meja F",
                capacity: 6,
                status: "available",
                position: "Gazebo 2",
            },
            {
                id: "O-07",
                label: "Meja G",
                capacity: 6,
                status: "available",
                position: "Taman belakang",
            },
            {
                id: "O-08",
                label: "Meja H",
                capacity: 8,
                status: "occupied",
                position: "Area lesehan",
            },
        ],
    },

    get currentTables() {
        if (!this.form.tableArea) return [];
        return this.tables[this.form.tableArea] || [];
    },

    isTableSelectable(table) {
        return (
            table.status === "available" && table.capacity >= this.form.guests
        );
    },

    selectArea(area) {
        this.form.tableArea = area;
        this.form.tableNumber = null;
    },

    selectTable(table) {
        if (!this.isTableSelectable(table)) return;
        this.form.tableNumber = table.id;
    },

    getTableLabel(id) {
        for (const area of Object.values(this.tables)) {
            const t = area.find((t) => t.id === id);
            if (t) return t.label + " — " + t.position;
        }
        return id;
    },

    selectedItems: [],
    paymentMethod: "cash",
    reservationCode: null,
    menuSearch: "",
    activeMenuCategory: "all",

    // ── DP Payment State ──
    dpNominal: 100000,
    dpNominals: [50000, 100000, 150000, 200000, 250000, 300000],
    get sisa() {
        return Math.max(0, this.selectedMenuTotal - parseInt(this.dpNominal));
    },

    menuCategories: [
        { id: "all",         name: "Semua",       icon: "🍽️" },
        { id: "makanan",     name: "Makanan",     icon: "🍛" },
        { id: "minuman",     name: "Minuman",     icon: "🥤" },
        { id: "dessert",     name: "Dessert",     icon: "🍰" },
        { id: "snack",       name: "Snack",       icon: "🍟" },
        { id: "paket",       name: "Paket",       icon: "📦" },
        { id: "seafood",     name: "Seafood",     icon: "🦐" },
        { id: "aneka-snack", name: "Aneka Snack", icon: "🥨" },
        { id: "aneka-sayur", name: "Aneka Sayur", icon: "🥦" },
        { id: "nasi-kotak",  name: "Nasi Kotak",  icon: "🍱" },
        { id: "acara-khusus",name: "Acara",       icon: "🎉" },
        { id: "iga",         name: "Iga",         icon: "🥩" },
    ],

    get filteredMenus() {
        let r = this.menus.filter(m => m.isStock !== false);
        if (this.activeMenuCategory !== "all") {
            r = r.filter(m => m.category === this.activeMenuCategory);
        }
        if (this.menuSearch.trim()) {
            const q = this.menuSearch.toLowerCase();
            r = r.filter(m =>
                m.name.toLowerCase().includes(q) ||
                (m.description || "").toLowerCase().includes(q)
            );
        }
        return r;
    },

    decreaseMenu(menu) {
        const existing = this.selectedItems.find(i => i.id === menu.id);
        if (!existing) return;
        if (existing.qty <= 1) {
            this.selectedItems = this.selectedItems.filter(i => i.id !== menu.id);
        } else {
            existing.qty--;
        }
    },

    formatPrice(price) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(price);
    },

    get selectedMenuTotal() {
        return this.selectedItems.reduce(
            (sum, item) => sum + item.price * item.qty,
            0,
        );
    },

    get canProceed() {
        if (this.step === 1) {
            return (
                this.form.date &&
                this.form.time &&
                this.form.tableArea &&
                this.form.tableNumber
            );
        }
        if (this.step === 2) {
            return this.selectedItems.length > 0;
        }
        if (this.step === 3) {
            return this.form.name && this.form.phone;
        }
        return true;
    },

    toggleMenu(menu) {
        const existing = this.selectedItems.find((item) => item.id === menu.id);
        if (existing) {
            existing.qty++;
        } else {
            this.selectedItems.push({ ...menu, qty: 1 });
        }
    },

    cancelReservation() {
        this.reset();
    },

    scrollToStep() {
        this.$nextTick(() => {
            const target = this.$refs?.stepTop || this.$el;
            if (target?.scrollIntoView) {
                target.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        });
    },
    nextStep() {
        if (this.step < 4) {
            this.step++;
            this.scrollToStep();
        }
    },
    prevStep() {
        if (this.step > 1) {
            this.step--;
            this.scrollToStep();
        }
    },

    async submit() {
        this.loading = true;
        this.message = "";
        try {
            const token = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content");
            const response = await fetch("/reservasi", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token || "",
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    date:          this.form.date,
                    time:          this.form.time,
                    guests:        this.form.guests,
                    name:          this.form.name,
                    phone:         this.form.phone,
                    email:         this.form.email,
                    notes:         this.form.notes,
                    tableArea:     this.form.tableArea,
                    tableNumber:   this.form.tableNumber,
                    paymentMethod: this.paymentMethod,
                    items: this.selectedItems.map((item) => ({
                        id:  item.id,
                        qty: item.qty,
                    })),
                }),
            });
            const data = await response.json();
            if (response.ok && data.success) {
                this.reservationCode = data.reservation_code
                    || ('#RES-' + Math.random().toString(36).substr(2, 8).toUpperCase());
                this.submitted = true;
                // Scroll ke atas untuk lihat struk
                this.$nextTick(() => {
                    const el = document.getElementById('receipt-wrapper');
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            } else {
                this.message = data.message || "Gagal membuat reservasi. Periksa kembali data Anda.";
                this.messageType = "error";
            }
        } catch (error) {
            // Fallback: tetap tampilkan struk meski API error
            this.reservationCode = '#RES-' + Math.random().toString(36).substr(2, 8).toUpperCase();
            this.submitted = true;
            this.$nextTick(() => {
                const el = document.getElementById('receipt-wrapper');
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        } finally {
            this.loading = false;
        }
    },

    downloadReceipt() {
        const self = this;
        const fmt  = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);
        const date = new Date().toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' });

        // Label metode pembayaran
        const pmLabels = {
            cash: 'Tunai', qris: 'QRIS', dana: 'DANA', ovo: 'OVO',
            shopeepay: 'ShopeePay', linkaja: 'LinkAja',
            mandiri: 'Bank Mandiri', bri: 'Bank BRI', bni: 'Bank BNI',
            bca: 'Bank BCA', permata: 'Bank Permata', ocbc: 'Bank OCBC',
        };
        const pmLabel = pmLabels[self.paymentMethod] || self.paymentMethod;

        // Rows menu
        const menuRows = self.selectedItems.map(item => `
            <tr>
                <td style="padding:6px 8px;border-bottom:1px solid #f0f0f0;">${item.name}</td>
                <td style="padding:6px 8px;border-bottom:1px solid #f0f0f0;text-align:center;">x${item.qty}</td>
                <td style="padding:6px 8px;border-bottom:1px solid #f0f0f0;text-align:right;">${fmt(item.price)}</td>
                <td style="padding:6px 8px;border-bottom:1px solid #f0f0f0;text-align:right;font-weight:600;">${fmt(item.price * item.qty)}</td>
            </tr>`).join('');

        const dpAmount   = parseInt(self.dpNominal) || 0;
        const sisaAmount = Math.max(0, self.selectedMenuTotal - dpAmount);

        // VA number jika VA
        const vaPrefix = { mandiri:'88808', bri:'26215', bni:'8277', bca:'57799', permata:'8629', ocbc:'9999' };
        const isVA     = ['mandiri','bri','bni','bca','permata','ocbc'].includes(self.paymentMethod);
        const isEwallet= ['qris','dana','ovo','shopeepay','linkaja'].includes(self.paymentMethod);
        const vaNum    = isVA ? (vaPrefix[self.paymentMethod] + (self.reservationCode || '').replace('#RES-','').replace('RES-','')) : '';

        const paymentSection = isVA ? `
            <div style="background:#eef2ff;border:1px solid #c7d2fe;border-radius:10px;padding:14px;margin-top:12px;">
                <p style="font-size:11px;color:#6366f1;font-weight:700;margin:0 0 6px;text-transform:uppercase;letter-spacing:.05em;">Virtual Account ${pmLabel}</p>
                <p style="font-size:22px;font-weight:800;color:#4338ca;letter-spacing:.1em;margin:0;">${vaNum}</p>
                <p style="font-size:11px;color:#6b7280;margin:6px 0 0;">Atas Nama: <strong>Restoran Nusantara</strong> &nbsp;·&nbsp; Batas Bayar: <strong style="color:#ef4444;">24 jam</strong></p>
            </div>` :
        isEwallet ? `
            <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:14px;margin-top:12px;text-align:center;">
                <p style="font-size:11px;color:#2563eb;font-weight:700;margin:0 0 8px;text-transform:uppercase;letter-spacing:.05em;">Bayar via ${pmLabel}</p>
                <div style="width:110px;height:110px;margin:0 auto 8px;background:#f3f4f6;border:2px dashed #d1d5db;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <span style="font-size:11px;color:#9ca3af;">QR Code</span>
                </div>
                <p style="font-size:11px;color:#6b7280;margin:0;">No. Ref: <strong>${self.reservationCode || '—'}</strong></p>
            </div>` :
        `<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px;margin-top:12px;">
            <p style="font-size:12px;color:#15803d;font-weight:600;margin:0;">💵 Bayar tunai saat kedatangan di kasir restoran.</p>
        </div>`;

        const html = `<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Struk Reservasi — Restoran Nusantara</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8fafc; color: #1f2937; }
  .page { max-width: 680px; margin: 0 auto; background: #fff; }
  @media print {
    body { background: white; }
    .no-print { display: none !important; }
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
  }
</style>
</head>
<body>
<div class="page">

  <div style="background:linear-gradient(135deg,#ea580c,#f97316);padding:28px 32px;color:white;text-align:center;">
    <div style="font-size:28px;font-weight:900;letter-spacing:.04em;margin-bottom:4px;">RESTORAN NUSANTARA</div>
    <div style="font-size:13px;opacity:.85;">Bukti Pembayaran Reservasi</div>
    <div style="margin-top:16px;background:rgba(255,255,255,.15);border-radius:12px;display:inline-block;padding:10px 28px;">
        <div style="font-size:11px;opacity:.8;margin-bottom:2px;text-transform:uppercase;letter-spacing:.06em;">Kode Reservasi</div>
        <div style="font-size:26px;font-weight:900;letter-spacing:.15em;font-family:monospace;">${self.reservationCode || '—'}</div>
    </div>
  </div>

  <div style="padding:24px 32px;">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">

        <div style="background:#f9fafb;border-radius:12px;padding:16px;border:1px solid #e5e7eb;">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;margin-bottom:10px;">Detail Reservasi</div>
            ${[
                ['Tanggal', self.form.date],
                ['Jam', self.form.time + ' WIB'],
                ['Tamu', self.form.guests + ' orang'],
                ['Area', self.form.tableArea ? (self.form.tableArea.charAt(0).toUpperCase() + self.form.tableArea.slice(1)) : '—'],
                ['Meja', self.getTableLabel(self.form.tableNumber)],
            ].map(([k,v]) => `
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;">
                <span style="color:#6b7280;">${k}</span>
                <span style="font-weight:600;">${v || '—'}</span>
            </div>`).join('')}
        </div>

        <div style="background:#f9fafb;border-radius:12px;padding:16px;border:1px solid #e5e7eb;">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;margin-bottom:10px;">Detail Tamu</div>
            ${[
                ['Nama', self.form.name],
                ['Telepon', self.form.phone],
                ['Email', self.form.email || '—'],
            ].map(([k,v]) => `
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;">
                <span style="color:#6b7280;">${k}</span>
                <span style="font-weight:600;">${v || '—'}</span>
            </div>`).join('')}
            ${self.form.notes ? `
            <div style="font-size:12px;margin-top:8px;padding-top:8px;border-top:1px solid #e5e7eb;">
                <span style="color:#6b7280;">Catatan:</span><br>
                <span style="font-weight:500;">${self.form.notes}</span>
            </div>` : ''}
        </div>
    </div>

    <div style="margin-bottom:20px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6b7280;margin-bottom:10px;">Menu Dipesan</div>
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#f3f4f6;">
                    <th style="padding:8px;text-align:left;font-size:11px;color:#6b7280;font-weight:600;">Menu</th>
                    <th style="padding:8px;text-align:center;font-size:11px;color:#6b7280;font-weight:600;">Qty</th>
                    <th style="padding:8px;text-align:right;font-size:11px;color:#6b7280;font-weight:600;">Harga</th>
                    <th style="padding:8px;text-align:right;font-size:11px;color:#6b7280;font-weight:600;">Subtotal</th>
                </tr>
            </thead>
            <tbody>${menuRows}</tbody>
            <tfoot>
                <tr style="background:#fff7ed;">
                    <td colspan="3" style="padding:10px 8px;font-weight:700;font-size:14px;">Total Menu</td>
                    <td style="padding:10px 8px;text-align:right;font-weight:800;font-size:16px;color:#ea580c;">${fmt(self.selectedMenuTotal)}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:12px;padding:16px;margin-bottom:20px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#ea580c;margin-bottom:10px;">Ringkasan Pembayaran</div>
        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;">
            <span style="color:#6b7280;">Metode Pembayaran</span>
            <span style="font-weight:600;">${pmLabel}</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;">
            <span style="color:#6b7280;">Total Pesanan</span>
            <span style="font-weight:600;">${fmt(self.selectedMenuTotal)}</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;">
            <span style="color:#6b7280;">DP Dibayar</span>
            <span style="font-weight:700;color:#ea580c;">${fmt(dpAmount)}</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:14px;padding-top:10px;border-top:2px solid #fdba74;margin-top:6px;">
            <span style="font-weight:700;">Sisa Pembayaran</span>
            <span style="font-weight:800;color:#dc2626;font-size:16px;">${fmt(sisaAmount)}</span>
        </div>
        <p style="font-size:11px;color:#9ca3af;margin-top:8px;">* Sisa pembayaran dibayarkan saat datang ke restoran.</p>
        ${paymentSection}
    </div>

    <div style="text-align:center;border-top:2px dashed #e5e7eb;padding-top:16px;">
        <p style="font-weight:800;font-size:15px;color:#1f2937;margin-bottom:4px;">Restoran Nusantara</p>
        <p style="font-size:12px;color:#6b7280;">Terima kasih! Tunjukkan struk ini kepada staff kami.</p>
        <p style="font-size:11px;color:#9ca3af;margin-top:4px;">Pertanyaan? Hubungi <strong>0812-3456-7890</strong></p>
        <p style="font-size:10px;color:#d1d5db;margin-top:12px;">Dicetak: ${date}</p>
    </div>

  </div>
</div>

<div class="no-print" style="text-align:center;padding:16px;background:#f8fafc;">
    <button onclick="window.print()" style="background:#ea580c;color:white;border:none;padding:12px 32px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;margin-right:8px;">🖨️ Cetak / Simpan PDF</button>
    <button onclick="window.close()" style="background:#6b7280;color:white;border:none;padding:12px 24px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;">✕ Tutup</button>
</div>

<script>
window.addEventListener('load', () => { window.print(); });
<\/script>
</body></html>`;

        const win = window.open('', '_blank', 'width=780,height=900,scrollbars=yes');
        if (!win) { alert('Popup diblokir. Izinkan popup untuk mencetak struk.'); return; }
        win.document.open();
        win.document.write(html);
        win.document.close();
    },

    reset() {
        this.step = 1;
        this.submitted = false;
        this.loading = false;
        this.message = "";
        this.selectedItems = [];
        this.paymentMethod = "cash";
        this.reservationCode = null;
        this.dpNominal = 100000;
        this.form = {
            date: "",
            time: "",
            guests: 2,
            name: "",
            phone: "",
            email: "",
            notes: "",
            tableArea: "",
            tableNumber: null,
        };
    },
}));

// =============================================
// ORDER STATUS — Real-time simulation
// =============================================
Alpine.data("orderStatus", () => ({
    orderId: "ORD-" + Math.random().toString(36).substr(2, 8).toUpperCase(),
    currentStatus: 1,
    estimasi: 30,
    statuses: [
        {
            id: 1,
            label: "Pesanan Diterima",
            icon: "1",
            time: "Baru saja",
            desc: "Pesanan kamu sudah kami terima",
            color: "bg-blue-500",
        },
        {
            id: 2,
            label: "Diproses",
            icon: "2",
            time: "",
            desc: "Pesanan sedang dipersiapkan",
            color: "bg-yellow-500",
        },
        {
            id: 3,
            label: "Dimasak",
            icon: "3",
            time: "",
            desc: "Chef sedang memasak pesananmu",
            color: "bg-orange-500",
        },
        {
            id: 4,
            label: "Siap Disajikan",
            icon: "4",
            time: "",
            desc: "Pesanan siap! Selamat menikmati!",
            color: "bg-green-500",
        },
    ],
    init() {
        let step = 1;
        const iv = setInterval(() => {
            if (step < 4) {
                step++;
                this.currentStatus = step;
                this.statuses[step - 1].time = "Baru saja";
                this.estimasi = Math.max(0, this.estimasi - 8);
                window.dispatchEvent(
                    new CustomEvent("toast", {
                        detail: {
                            message: `Status: ${this.statuses[step - 1].label}`,
                            type: "info",
                        },
                    }),
                );
            } else {
                clearInterval(iv);
            }
        }, 4000);
    },
}));

// =============================================
// CHECKOUT COMPONENT
// =============================================
Alpine.data("checkout", () => ({
    orderType: "dine-in",
    paymentMethod: "cash",
    voucherCode: "",
    voucherMessage: "",
    voucherSuccess: false,
    isProcessing: false,
    orderPlaced: false,
    paymentStatus: "",
    orderReference: "",
    proofFileName: "",
    form: {
        name: "",
        phone: "",
        email: "",
        address: "",
        tableNumber: "",
        notes: "",
        cardNumber: "",
        cardExpiry: "",
        cardCvv: "",
    },
    paymentLabels: {
        cash: "Tunai",
        "qris-dana": "DANA",
        "qris-ovo": "OVO",
        "qris-gopay": "GoPay",
        "va-bca": "Virtual Account BCA",
        "va-bri": "Virtual Account BRI",
        "va-bni": "Virtual Account BNI",
        "va-mandiri": "Virtual Account Mandiri",
        "va-cimb": "Virtual Account CIMB Niaga",
        "va-permata": "Virtual Account Permata",
        "va-danamon": "Virtual Account Danamon",
        "manual-bca": "Transfer BCA",
        "manual-bri": "Transfer BRI",
        "manual-bni": "Transfer BNI",
        "manual-mandiri": "Transfer Mandiri",
        "manual-bsi": "Transfer BSI",
        "manual-permata": "Transfer Permata",
        "manual-cimb": "Transfer CIMB Niaga",
        "card-visa": "Visa",
        "card-mastercard": "Mastercard",
        "card-jcb": "JCB",
        "paylater-shopee": "Shopee PayLater",
        "paylater-kredivo": "Kredivo",
        "paylater-akulaku": "Akulaku",
    },
    paymentAccount: {
        "va-bca": "880 123 456 789",
        "va-bri": "809 321 654 123",
        "va-bni": "700 456 123 789",
        "va-mandiri": "888 321 654 000",
        "va-cimb": "803 987 654 321",
        "va-permata": "897 234 561 000",
        "va-danamon": "889 654 123 000",
        "manual-bca": "880 123 456 789",
        "manual-bri": "809 321 654 123",
        "manual-bni": "700 456 123 789",
        "manual-mandiri": "888 321 654 000",
        "manual-bsi": "700 111 222 333",
        "manual-permata": "897 234 561 000",
        "manual-cimb": "803 987 654 321",
    },
    paymentRequiresProof() {
        return this.paymentMethod.startsWith("manual-");
    },
    paymentLabel() {
        return this.paymentLabels[this.paymentMethod] || "Metode Pembayaran";
    },
    paymentAccountNumber() {
        return this.paymentAccount[this.paymentMethod] || "123 456 789 000";
    },
    applyVoucher() {
        const r = Alpine.store("cart").applyVoucher(this.voucherCode);
        this.voucherMessage = r.message;
        this.voucherSuccess = r.success;
    },
    placeOrder() {
        this.isProcessing = true;
        setTimeout(() => {
            this.isProcessing = false;
            this.orderPlaced = true;
            this.orderReference =
                "ORD-" + Math.random().toString(36).substr(2, 8).toUpperCase();
            if (
                this.paymentMethod.startsWith("manual-") ||
                this.paymentMethod.startsWith("va-")
            ) {
                this.paymentStatus = "Menunggu Verifikasi Pembayaran";
            } else if (
                this.paymentMethod.startsWith("qris-") ||
                this.paymentMethod.startsWith("card-") ||
                this.paymentMethod.startsWith("paylater-")
            ) {
                this.paymentStatus = "Pembayaran berhasil. Sedang diproses.";
            } else {
                this.paymentStatus = "Bayar di tempat saat pesanan tiba.";
            }
            Alpine.store("cart").clear();
        }, 2500);
    },
    formatPrice(p) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(p);
    },
}));

// =============================================
// QR CODE COMPONENT
// =============================================
Alpine.data("qrCode", () => ({
    show: false,
    url: window.location.origin + "/#menu",
    open() {
        this.show = true;
    },
    close() {
        this.show = false;
    },
    // Generate QR via Google Charts API (no library needed)
    get qrSrc() {
        return `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(this.url)}&bgcolor=ffffff&color=1a1a1a&margin=10`;
    },
}));

// =============================================
// GALLERY LIGHTBOX
// =============================================
Alpine.data("galleryLightbox", () => ({
    open: false,
    current: 0,
    images: [],
    openAt(images, index) {
        this.images = images;
        this.current = index;
        this.open = true;
        document.body.style.overflow = "hidden";
    },
    close() {
        this.open = false;
        document.body.style.overflow = "";
    },
    prev() {
        this.current =
            (this.current - 1 + this.images.length) % this.images.length;
    },
    next() {
        this.current = (this.current + 1) % this.images.length;
    },
    init() {
        window.addEventListener("keydown", (e) => {
            if (!this.open) return;
            if (e.key === "ArrowLeft") this.prev();
            if (e.key === "ArrowRight") this.next();
            if (e.key === "Escape") this.close();
        });
    },
}));

// =============================================
// INIT
// =============================================
window.Alpine = Alpine;
Alpine.start();
document.addEventListener("DOMContentLoaded", () => {
    Alpine.store("theme").apply();
});
