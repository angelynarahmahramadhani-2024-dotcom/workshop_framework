@extends('layouts.app')

@section('title', 'Mini Kantin Online — Payment Gateway')

@section('page-title', 'Mini Kantin Online')
@section('page-icon', 'mdi-food')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Payment Gateway</li>
@endsection

@push('styles')
<style>
    /* ─── Cards ───────────────────────────── */
    .glass-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(15,23,42,0.04), 0 4px 12px rgba(15,23,42,0.06);
    }

    .card-header-custom {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
    }

    .step-number {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #a855f7, #7c3aed);
        color: #fff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.05rem;
    }

    /* ─── Select ──────────────────────────── */
    .form-select-custom {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.25s ease;
    }
    .form-select-custom:focus {
        border-color: #a855f7;
        box-shadow: 0 0 0 3px rgba(168,85,247,0.15);
    }

    /* ─── Menu Grid ───────────────────────── */
    .menu-card {
        background: #fff;
        border: 2px solid #f1f5f9;
        border-radius: 10px;
        padding: 16px;
        transition: all 0.25s ease;
        cursor: pointer;
    }
    .menu-card:hover {
        border-color: #c4b5fd;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(124,58,237,0.1);
    }
    .menu-card .menu-name {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 4px;
    }
    .menu-card .menu-price {
        font-weight: 700;
        font-size: 1rem;
        color: #7c3aed;
    }

    .btn-add-cart {
        background: linear-gradient(135deg, #a855f7, #7c3aed);
        border: none;
        color: #fff;
        font-weight: 600;
        font-size: 0.82rem;
        padding: 6px 16px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-add-cart:hover {
        background: linear-gradient(135deg, #9333ea, #6d28d9);
        transform: scale(1.03);
        color: #fff;
    }

    /* ─── Cart ────────────────────────────── */
    .cart-sticky { position: sticky; top: 16px; }

    .cart-table th {
        font-weight: 600;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        border-bottom: 2px solid #f1f5f9;
        padding: 8px 6px;
    }
    .cart-table td {
        vertical-align: middle;
        padding: 8px 6px;
        font-size: 0.9rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .cart-table tfoot th {
        border-top: 2px solid #ddd6fe;
        font-size: 0.95rem;
    }

    .qty-input {
        width: 56px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        padding: 4px;
        font-size: 0.88rem;
    }
    .qty-input:focus {
        border-color: #a855f7;
        outline: none;
        box-shadow: 0 0 0 3px rgba(168,85,247,0.15);
    }

    .btn-remove {
        width: 28px;
        height: 28px;
        border: none;
        background: #f1f5f9;
        color: #ef4444;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-remove:hover { background: #fef2f2; color: #dc2626; }

    /* ─── Checkout Button ─────────────────── */
    .btn-checkout {
        background: linear-gradient(135deg, #a855f7, #7c3aed);
        border: none;
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        padding: 14px 24px;
        border-radius: 10px;
        width: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(124,58,237,0.2);
    }
    .btn-checkout:hover:not(:disabled) {
        background: linear-gradient(135deg, #9333ea, #6d28d9);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(124,58,237,0.3);
        color: #fff;
    }
    .btn-checkout:disabled {
        background: #e2e8f0;
        color: #94a3b8;
        box-shadow: none;
        cursor: not-allowed;
    }

    /* ─── Snap Info ───────────────────────── */
    .snap-info {
        background: linear-gradient(135deg, #f5f3ff, #ede9fe);
        border: 1px solid #c4b5fd;
        border-radius: 10px;
        padding: 14px;
        font-size: 0.84rem;
        color: #5b21b6;
        text-align: center;
    }

    /* ─── Skeleton ────────────────────────── */
    .skeleton {
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 8px;
    }
    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .empty-state {
        text-align: center;
        padding: 32px 16px;
        color: #94a3b8;
    }
    .empty-state .empty-icon { font-size: 2.5rem; margin-bottom: 8px; }
    .empty-state .empty-text { font-size: 0.9rem; font-weight: 500; }

    @media (max-width: 991.98px) {
        .cart-sticky { position: static; }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-7 grid-margin">
        {{-- Step 1: Pilih Vendor --}}
        <div class="glass-card p-4 mb-4" id="vendorCard">
            <div class="card-header-custom">
                <div class="step-number">1</div>
                <h5>Pilih Vendor</h5>
            </div>
            <select id="vendorSelect" class="form-select form-select-custom">
                <option value="">— Pilih vendor kantin —</option>
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id_vendor }}">{{ $vendor->nama_vendor }}</option>
                @endforeach
            </select>
        </div>

        {{-- Step 2: Pilih Menu --}}
        <div class="glass-card p-4" id="menuCard">
            <div class="card-header-custom">
                <div class="step-number">2</div>
                <h5>Pilih Menu</h5>
            </div>
            <div id="menuWrapper">
                <div class="empty-state">
                    <div class="empty-icon">🏪</div>
                    <div class="empty-text">Silakan pilih vendor terlebih dahulu</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 grid-margin">
        {{-- Step 3: Keranjang & Checkout --}}
        <div class="glass-card p-4 cart-sticky" id="cartCard">
            <div class="card-header-custom">
                <div class="step-number">3</div>
                <h5>Keranjang & Checkout</h5>
            </div>

            {{-- Info Snap --}}
            <div class="snap-info mb-3">
                💳 Pilih metode pembayaran <b>(VA, QRIS, dll)</b> langsung di halaman pembayaran Midtrans
                setelah checkout.
            </div>

            {{-- Cart Table --}}
            <div class="mb-3" style="max-height:340px;overflow-y:auto;">
                <table class="table cart-table mb-0" id="cartTable">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cartBody">
                        <tr>
                            <td colspan="4">
                                <div class="empty-state" style="padding:20px 0;">
                                    <div class="empty-icon">🛒</div>
                                    <div class="empty-text">Keranjang masih kosong</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <th class="text-end" id="cartTotal" style="color:#7c3aed;">Rp 0</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Checkout Button --}}
            <button class="btn btn-checkout" id="checkoutBtn" disabled>
                🛍️ Bayar Sekarang
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Axios & SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Midtrans Snap.js --}}
@if(config('services.midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endif

<script>
    // ─── State ───────────────────────────────
    const state = {
        selectedVendor: null,
        menus: [],
        cart: [],
    };

    // ─── DOM Refs ────────────────────────────
    const vendorSelect = document.getElementById('vendorSelect');
    const menuWrapper = document.getElementById('menuWrapper');
    const cartBody = document.getElementById('cartBody');
    const cartTotal = document.getElementById('cartTotal');
    const checkoutBtn = document.getElementById('checkoutBtn');

    axios.defaults.headers.common['X-CSRF-TOKEN'] =
        document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ─── Vendor Change ───────────────────────
    vendorSelect.addEventListener('change', async function () {
        const vendorId = this.value;
        state.selectedVendor = vendorId ? Number(vendorId) : null;
        state.cart = [];
        renderCart();

        if (!state.selectedVendor) {
            menuWrapper.innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon">🏪</div>
                    <div class="empty-text">Silakan pilih vendor terlebih dahulu</div>
                </div>`;
            return;
        }

        // Loading skeleton
        menuWrapper.innerHTML = `
            <div class="row g-3">
                ${[1, 2, 3, 4].map(() => `
                    <div class="col-sm-6">
                        <div class="skeleton" style="height:100px;"></div>
                    </div>`).join('')}
            </div>`;

        try {
            const response = await axios.get('{{ route("order.menu") }}', {
                params: { idvendor: state.selectedVendor }
            });
            state.menus = response.data.data || [];
            renderMenu();
        } catch (error) {
            menuWrapper.innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon">❌</div>
                    <div class="empty-text text-danger">Gagal memuat menu. Coba lagi.</div>
                </div>`;
        }
    });

    // ─── Render Menu Grid ────────────────────
    function renderMenu() {
        if (state.menus.length === 0) {
            menuWrapper.innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <div class="empty-text">Vendor ini belum memiliki menu</div>
                </div>`;
            return;
        }

        menuWrapper.innerHTML = `<div class="row g-3">${state.menus.map((menu, i) => `
            <div class="col-sm-6">
                <div class="menu-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="menu-name">${esc(menu.nama_menu)}</div>
                        <div class="menu-price">${rupiah(Number(menu.harga))}</div>
                    </div>
                    <button class="btn btn-add-cart mt-3" onclick="addToCart(${menu.idmenu})">
                        + Tambah
                    </button>
                </div>
            </div>
        `).join('')}</div>`;
    }

    // ─── Cart Functions ──────────────────────
    function addToCart(idmenu) {
        const menu = state.menus.find(m => m.idmenu === idmenu);
        if (!menu) return;

        const found = state.cart.find(item => item.idmenu === idmenu);
        if (found) {
            found.jumlah += 1;
        } else {
            state.cart.push({
                idmenu: menu.idmenu,
                nama_menu: menu.nama_menu,
                harga: Number(menu.harga),
                jumlah: 1,
            });
        }
        renderCart();
    }

    function removeFromCart(idmenu) {
        state.cart = state.cart.filter(item => item.idmenu !== idmenu);
        renderCart();
    }

    function changeQty(idmenu, qty) {
        const item = state.cart.find(x => x.idmenu === idmenu);
        if (!item) return;
        const q = Number(qty);
        item.jumlah = q > 0 ? q : 1;
        renderCart();
    }

    function renderCart() {
        if (state.cart.length === 0) {
            cartBody.innerHTML = `
                <tr>
                    <td colspan="4">
                        <div class="empty-state" style="padding:20px 0;">
                            <div class="empty-icon">🛒</div>
                            <div class="empty-text">Keranjang masih kosong</div>
                        </div>
                    </td>
                </tr>`;
            cartTotal.textContent = 'Rp 0';
            checkoutBtn.disabled = true;
            return;
        }

        let total = 0;
        cartBody.innerHTML = state.cart.map(item => {
            const subtotal = item.harga * item.jumlah;
            total += subtotal;
            return `
                <tr>
                    <td style="font-weight:500;">${esc(item.nama_menu)}</td>
                    <td class="text-center">
                        <input type="number" class="qty-input" min="1"
                            value="${item.jumlah}"
                            onchange="changeQty(${item.idmenu}, this.value)">
                    </td>
                    <td class="text-end" style="font-weight:600;">${rupiah(subtotal)}</td>
                    <td class="text-end">
                        <button class="btn-remove" onclick="removeFromCart(${item.idmenu})">✕</button>
                    </td>
                </tr>`;
        }).join('');

        cartTotal.textContent = rupiah(total);
        checkoutBtn.disabled = false;
    }

    // ─── Checkout dengan Midtrans Snap ───────
    checkoutBtn.addEventListener('click', async function () {
        if (!state.selectedVendor || state.cart.length === 0) return;

        checkoutBtn.disabled = true;
        checkoutBtn.innerHTML = '⏳ Memproses...';

        try {
            const payload = {
                idvendor: state.selectedVendor,
                items: state.cart.map(item => ({
                    idmenu: item.idmenu,
                    jumlah: item.jumlah,
                })),
            };

            const response = await axios.post('{{ route("order.checkout") }}', payload);
            const result = response.data.data;
            const snapToken = result.snap_token;

            if (!snapToken) {
                throw new Error(result.error || 'Gagal mendapatkan token pembayaran.');
            }

            // Buka popup Midtrans Snap
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil! 🎉',
                        html: `
                            <div style="text-align:left;font-size:0.95rem;line-height:1.8;">
                                <div><b>Order ID:</b> ${esc(result.order_id)}</div>
                                <div><b>Metode Bayar:</b> ${esc(result.payment_type)}</div>
                                <div><b>Status:</b> <span style="color:#10b981;font-weight:700;">LUNAS ✅</span></div>
                            </div>
                            <div style="margin-top:10px;padding:10px;background:#ecfdf5;border-radius:8px;font-size:0.85rem;color:#047857;">
                                Anda akan diarahkan ke halaman QR Code pesanan.
                            </div>`,
                        confirmButtonColor: '#7c3aed',
                        confirmButtonText: 'Lihat QR Code',
                    }).then(() => {
                        const idpesanan = response.data.data.idpesanan;
                        window.location.href = `/order/qrcode/${idpesanan}`;
                    });
                    state.cart = [];
                    renderCart();
                },
                onPending: function(result) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Menunggu Pembayaran ⏳',
                        html: `
                            <div style="text-align:left;font-size:0.95rem;line-height:1.8;">
                                <div><b>Order ID:</b> ${esc(result.order_id)}</div>
                                <div><b>Metode Bayar:</b> ${esc(result.payment_type)}</div>
                                <div><b>Status:</b> <span style="color:#f59e0b;font-weight:700;">PENDING</span></div>
                            </div>
                            <div style="margin-top:10px;padding:10px;background:#fffbeb;border-radius:8px;font-size:0.85rem;color:#92400e;">
                                Silakan selesaikan pembayaran sesuai instruksi.
                            </div>`,
                        confirmButtonColor: '#7c3aed',
                        confirmButtonText: 'OK',
                    });
                    state.cart = [];
                    renderCart();
                },
                onError: function(result) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal',
                        text: 'Terjadi kesalahan saat memproses pembayaran.',
                        confirmButtonColor: '#ef4444',
                    });
                },
                onClose: function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pembayaran Dibatalkan',
                        text: 'Anda menutup halaman pembayaran.',
                        confirmButtonColor: '#7c3aed',
                    });
                },
            });

        } catch (error) {
            const msg = error?.response?.data?.message || error.message || 'Checkout gagal diproses.';
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: msg,
                confirmButtonColor: '#ef4444',
            });
        } finally {
            checkoutBtn.disabled = false;
            checkoutBtn.innerHTML = '🛍️ Bayar Sekarang';
        }
    });

    // ─── Helpers ─────────────────────────────
    function rupiah(n) {
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    function esc(v) {
        return String(v)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Expose to global (for onclick handlers)
    window.addToCart = addToCart;
    window.removeFromCart = removeFromCart;
    window.changeQty = changeQty;
</script>
@endpush