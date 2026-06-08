<!DOCTYPE html>
<html lang="en" class="bg-zinc-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Viewer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Syne', 'sans-serif'],
                        mono:    ['JetBrains Mono', 'monospace'],
                    },
                    keyframes: {
                        fadeUp: {
                            '0%':   { opacity: '0', transform: 'translateY(16px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    },
                    animation: {
                        'fade-up': 'fadeUp 0.35s ease both',
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'JetBrains Mono', monospace; }
        .card-stagger:nth-child(1) { animation-delay: 0ms; }
        .card-stagger:nth-child(2) { animation-delay: 60ms; }
        .card-stagger:nth-child(3) { animation-delay: 120ms; }
        .card-stagger:nth-child(4) { animation-delay: 180ms; }
        .card-stagger:nth-child(5) { animation-delay: 240ms; }
        .card-stagger:nth-child(6) { animation-delay: 300ms; }
        .card-stagger:nth-child(7) { animation-delay: 360ms; }
        .card-stagger:nth-child(8) { animation-delay: 420ms; }
        .rec-stagger:nth-child(1)  { animation-delay: 0ms; }
        .rec-stagger:nth-child(2)  { animation-delay: 80ms; }
        .rec-stagger:nth-child(3)  { animation-delay: 160ms; }
        .rec-stagger:nth-child(4)  { animation-delay: 240ms; }
    </style>
</head>
<body class="min-h-screen bg-zinc-950 text-zinc-100 px-4 py-10 pb-20">

    {{-- Header --}}
    <header class="max-w-7xl mx-auto flex items-baseline gap-3 mb-10">
        <h1 class="font-display font-extrabold text-5xl tracking-tight leading-none">
            Cart<span class="text-[#d9f547]">.</span>
        </h1>
        <span class="text-zinc-500 text-xs ml-auto">Laravel · Tailwind · GPT-4o</span>
    </header>

    {{-- Search --}}
    <div class="max-w-7xl mx-auto mb-10">
        <label for="cart-input" class="block text-[10px] uppercase tracking-widest text-zinc-500 mb-2">
            Cart ID
        </label>
        <div class="flex gap-3">
            <input
                id="cart-input"
                type="text"
                placeholder="e.g. 1"
                autocomplete="off"
                class="flex-1 bg-zinc-900 border border-zinc-800 rounded-lg px-4 py-3 text-zinc-100 font-mono text-sm placeholder-zinc-600 outline-none focus:border-[#d9f547] transition-colors"
            />
            <button
                id="fetch-btn"
                onclick="fetchCart()"
                class="bg-[#d9f547] text-zinc-950 font-display font-bold text-sm px-6 rounded-lg hover:bg-[#c2db38] active:scale-95 transition-all disabled:opacity-40 disabled:cursor-not-allowed"
            >
                Fetch Cart
            </button>
        </div>
    </div>

    {{-- Error (hidden by default) --}}
    <div id="error-box" class="hidden max-w-7xl mx-auto bg-red-950/50 border border-red-700 text-red-400 rounded-lg px-4 py-3 text-sm mb-6"></div>

    {{-- Main layout: cart left + sidebar right (hidden by default) --}}
    <div id="cart-results" class="hidden max-w-7xl mx-auto flex flex-col lg:flex-row gap-8 items-start">

        {{-- LEFT: Cart --}}
        <div class="flex-1 min-w-0">

            {{-- Summary bar --}}
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <h2 class="font-display font-bold text-xl">
                    Cart <span id="result-cart-id" class="text-[#d9f547]"></span>
                </h2>
                <span id="result-item-count" class="text-[10px] uppercase tracking-widest text-zinc-500 border border-zinc-800 rounded-full px-3 py-1"></span>
                <span id="result-grand-total" class="ml-auto font-display font-bold text-2xl text-[#d9f547]"></span>
            </div>

            {{-- Cards grid --}}
            <div id="cards-grid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                <template id="card-template">
                    <div class="card-stagger animate-fade-up bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden flex flex-col hover:border-zinc-600 hover:-translate-y-0.5 transition-all">
                        <img data-field="image" class="w-full aspect-video object-cover bg-zinc-900" loading="lazy" alt="">
                        <div data-field="image-placeholder" class="hidden w-full aspect-video bg-zinc-900 flex items-center justify-center text-3xl text-zinc-700">📦</div>
                        <div class="p-4 flex flex-col gap-2 flex-1">
                            <p data-field="title" class="font-display font-bold text-base leading-snug"></p>
                            <p data-field="sku" class="text-[10px] tracking-widest text-zinc-600 uppercase"></p>
                            <p data-field="description" class="text-xs text-zinc-500 leading-relaxed line-clamp-2"></p>
                            <hr class="border-zinc-800 my-1">
                            <div class="flex justify-between text-xs">
                                <span class="text-zinc-500">Unit price</span>
                                <span data-field="unit-price"></span>
                            </div>
                            <div data-field="discount-row" class="flex justify-between text-xs">
                                <span class="text-zinc-500">Discount</span>
                                <span data-field="discount" class="text-amber-400"></span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-zinc-500">After discount</span>
                                <span data-field="final-price" class="text-[#d9f547] font-bold"></span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-zinc-500">Qty</span>
                                <span data-field="quantity"></span>
                            </div>
                            <hr class="border-zinc-800 my-1">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-zinc-500">Line total</span>
                                <span data-field="line-total" class="font-display font-bold text-lg text-[#d9f547]"></span>
                            </div>
                        </div>
                        <div class="px-4 py-3 border-t border-zinc-800 flex justify-between text-[11px]">
                            <span data-field="stock"></span>
                            <span data-field="shipping" class="text-zinc-600 text-right"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- RIGHT: Recommendations sidebar --}}
        <div class="lg:w-72 xl:w-80 shrink-0 w-full">
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5 sticky top-8">

                <div class="flex items-center gap-2 mb-4">
                    <span class="text-lg">✨</span>
                    <h3 class="font-display font-bold text-sm uppercase tracking-widest text-zinc-300">
                        You might also like
                    </h3>
                </div>

                {{-- Loading text (shown while fetching) --}}
                <p id="rec-loading" class="text-xs text-zinc-600 py-4 text-center">
                    Fetching recommendations…
                </p>

                {{-- Error --}}
                <p id="rec-error" class="hidden text-xs text-red-400 py-4 text-center">
                    Could not load recommendations.
                </p>

                {{-- Recommendations list --}}
                <ul id="rec-list" class="hidden flex flex-col gap-3">
                    {{-- Populated by JS --}}
                </ul>

                {{-- Recommendation item template --}}
                <template id="rec-template">
                    <li class="rec-stagger animate-fade-up flex flex-col gap-1 border-b border-zinc-800 pb-3 last:border-0 last:pb-0">
                        <p data-field="rec-name" class="font-display font-bold text-sm text-zinc-100"></p>
                        <p data-field="rec-reason" class="text-xs text-zinc-500 leading-relaxed"></p>
                    </li>
                </template>

                <p class="mt-5 text-[10px] text-zinc-700 text-center">Powered by GPT-4o</p>
            </div>
        </div>

    </div>

<script>
    const input       = document.getElementById('cart-input');
    const btn         = document.getElementById('fetch-btn');
    const errorBox    = document.getElementById('error-box');
    const cartResults = document.getElementById('cart-results');
    const cardsGrid   = document.getElementById('cards-grid');
    const template    = document.getElementById('card-template');

    const recLoading  = document.getElementById('rec-loading');
    const recError    = document.getElementById('rec-error');
    const recList     = document.getElementById('rec-list');
    const recTemplate = document.getElementById('rec-template');

    input.addEventListener('keydown', e => { if (e.key === 'Enter') fetchCart(); });

    function showOnly(el) {
        [errorBox, cartResults].forEach(e => e.classList.add('hidden'));
        if (el) el.classList.remove('hidden');
    }

    async function fetchCart() {
        const cartId = input.value.trim();
        if (!cartId) { input.focus(); return; }

        btn.disabled = true;

        try {
            const res = await fetch(`/api/getcart/${encodeURIComponent(cartId)}`);

            if (res.status === 404) { showError(`No cart found with ID "${cartId}".`); return; }
            if (!res.ok)            { showError(`Server error (${res.status}). Please try again.`); return; }

            const data  = await res.json();
            const items = Array.isArray(data) ? data : (data.data ?? []);

            if (items.length === 0) { showError(`Cart #${cartId} is empty.`); return; }

            renderCart(cartId, items);
            fetchRecommendations(cartId);

        } catch (err) {
            showError('Network error — could not reach the server.');
        } finally {
            btn.disabled = false;
        }
    }

    function showError(msg) {
        errorBox.textContent = '⚠ ' + msg;
        showOnly(errorBox);
    }

    function renderCart(cartId, items) {
        const grandTotal = items
            .reduce((sum, i) => sum + parseFloat(i.final_price) * i.quantity, 0)
            .toFixed(2);

        document.getElementById('result-cart-id').textContent     = '#' + cartId;
        document.getElementById('result-item-count').textContent  = items.length + ' item' + (items.length !== 1 ? 's' : '');
        document.getElementById('result-grand-total').textContent = '€' + grandTotal;

        cardsGrid.querySelectorAll('.card-stagger').forEach(c => c.remove());
        items.forEach(item => cardsGrid.appendChild(buildCard(item)));

        recLoading.classList.remove('hidden');
        recError.classList.add('hidden');
        recList.classList.add('hidden');
        recList.querySelectorAll('li').forEach(li => li.remove());

        showOnly(cartResults);
    }

    async function fetchRecommendations(cartId) {
        try {
            const res = await fetch(`/api/getcart/${encodeURIComponent(cartId)}/recommendations`);

            if (!res.ok) { showRecError(); return; }

            const recs = await res.json();

            if (!Array.isArray(recs) || recs.length === 0) { showRecError(); return; }

            recs.forEach(rec => {
                const item = recTemplate.content.cloneNode(true).firstElementChild;
                item.querySelector('[data-field="rec-name"]').textContent   = rec.name;
                item.querySelector('[data-field="rec-reason"]').textContent = rec.reason;
                recList.appendChild(item);
            });

            recLoading.classList.add('hidden');
            recList.classList.remove('hidden');

        } catch (err) {
            showRecError();
        }
    }

    function showRecError() {
        recLoading.classList.add('hidden');
        recError.classList.remove('hidden');
    }

    function buildCard(item) {
        const p          = item.product_info ?? {};
        const unitPrice  = parseFloat(item.unit_price).toFixed(2);
        const discount   = item.discount_percentage ? parseFloat(item.discount_percentage).toFixed(2) : null;
        const finalPrice = parseFloat(item.final_price).toFixed(2);
        const lineTotal  = (parseFloat(item.final_price) * item.quantity).toFixed(2);
        const stock      = p.stock ?? null;

        const card = template.content.cloneNode(true).firstElementChild;
        const f    = field => card.querySelector(`[data-field="${field}"]`);

        if (p.image) {
            f('image').src = p.image;
            f('image').alt = p.title ?? '';
            f('image-placeholder').classList.add('hidden');
        } else {
            f('image').classList.add('hidden');
            f('image-placeholder').classList.remove('hidden');
        }

        f('title').textContent       = p.title ?? 'Unknown product';
        f('description').textContent = p.description ?? '';
        f('unit-price').textContent  = '€' + unitPrice;
        f('final-price').textContent = '€' + finalPrice;
        f('quantity').textContent    = '× ' + item.quantity;
        f('line-total').textContent  = '€' + lineTotal;
        f('shipping').textContent    = p.shippingInformation ?? '';

        if (p.sku) { f('sku').textContent = p.sku; }
        else       { f('sku').classList.add('hidden'); }

        if (discount) { f('discount').textContent = '−' + discount + '%'; }
        else          { f('discount-row').classList.add('hidden'); }

        const stockEl = f('stock');
        if (stock === null)   { stockEl.textContent = '—';                stockEl.className = 'text-zinc-600'; }
        else if (stock === 0) { stockEl.textContent = 'Out of stock';     stockEl.className = 'text-red-400'; }
        else                  { stockEl.textContent = stock + ' in stock'; stockEl.className = 'text-emerald-400'; }

        return card;
    }
</script>
</body>
</html>