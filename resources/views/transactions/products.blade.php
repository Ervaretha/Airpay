@extends('layouts.app')

@section('title','Transaksi - Produk')

@push('styles')
<style>
/* small page-specific style to match screenshots */
.toolbar {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom: 12px;
}
.card-body .table td, .card-body .table th { vertical-align: middle; }
.qty-control {
    display:flex;
    align-items:center;
    gap:6px;
}
.badge-action {
    width:34px;
    height:34px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border-radius:6px;
}
.search-input { max-width:420px; }
.btn-cart {
    background:#2ecc71;
    color:#fff;
    border:none;
    font-weight:600;
}
</style>
@endpush

@section('content')
<div class="container-fluid">

    <div class="card p-3">
        <div class="mb-3">
            <input type="text" class="form-control search-input" placeholder="Cari Produk..." id="productSearch">
        </div>

        <div class="toolbar mb-2">
            <h5 class="mb-0">Daftar Produk</h5>
            <div class="text-end">
                <button type="button" class="btn btn-success">
                    <a href="{{ route('cart.view') }}" class="btn btn-cart text-white">
                    <i class="fas fa-shopping-cart me-2"></i> Keranjang
                    @if(!empty($cart))
                        <span class="badge bg-white text-dark ms-2">{{ count($cart) }}</span>
                    @endif
                </a>
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="productTableBody" class="text-center">
                    @foreach($products as $product)
<tr data-name="{{ strtolower($product->name) }}"
    class="{{ $product->stock == 0 ? 'table-secondary opacity-50' : '' }}"
    style="{{ $product->stock == 0 ? 'pointer-events:none;' : '' }}"
>
    <td>{{ $product->code ?? 'PRD-' . str_pad($product->id,3,'0',STR_PAD_LEFT) }}</td>
    <td>{{ $product->name }}</td>
    <td>{{ $product->category->name ?? '-' }}</td>
    <td>Rp {{ number_format($product->price,0,',','.') }}</td>

    <td>
        <div class="qty-control">
            <button class="btn btn-sm btn-outline-secondary btn-decrease"
                data-id="{{ $product->id }}"
                {{ $product->stock == 0 ? 'disabled' : '' }}
            >&#x2013;</button>

            <input type="number"
                min="1"
                value="1"
                class="form-control form-control-sm qty-input"
                style="width:70px;"
                data-id="{{ $product->id }}"
                {{ $product->stock == 0 ? 'disabled' : '' }}
            >

            <button class="btn btn-sm btn-outline-secondary btn-increase"
                data-id="{{ $product->id }}"
                {{ $product->stock == 0 ? 'disabled' : '' }}
            >+</button>
        </div>
    </td>

    <td>
        @if ($product->stock > 0)
            <button class="btn btn-sm btn-primary add-to-cart text-white"
                data-id="{{ $product->id }}">
                <i class="fas fa-cart-plus"></i>
            </button>
        @else
            <button class="btn btn-sm btn-secondary text-white" disabled style="cursor:not-allowed;">
                Out of Stock
            </button>
        @endif
    </td>
</tr>
@endforeach

                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-2">
            <div>Menampilkan {{ $products->firstItem() ?? 0 }} dari {{ $products->total() ?? $products->count() }} produk</div>
            <div>{{ $products->links() }}</div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Berhasil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="successMessage">
                Produk berhasil ditambahkan ke keranjang.
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Gagal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="errorMessage">
                Terjadi kesalahan.
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

document.addEventListener('DOMContentLoaded', function(){

    // Bootstrap modals
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');

    // Search Filter
    const search = document.getElementById('productSearch');
    const tbody = document.getElementById('productTableBody');

    search.addEventListener('input', function(){
        const q = this.value.toLowerCase();
        Array.from(tbody.querySelectorAll('tr')).forEach(tr=>{
            const name = tr.getAttribute('data-name') || '';
            tr.style.display = name.includes(q) ? '' : 'none';
        });
    });

    // Increase / Decrease Quantity
    document.querySelectorAll('.btn-increase').forEach(btn=>{
        btn.addEventListener('click', ()=> {
            const id = btn.dataset.id;
            const input = document.querySelector('.qty-input[data-id="'+id+'"]');
            input.value = parseInt(input.value || 1) + 1;
        });
    });

    document.querySelectorAll('.btn-decrease').forEach(btn=>{
        btn.addEventListener('click', ()=> {
            const id = btn.dataset.id;
            const input = document.querySelector('.qty-input[data-id="'+id+'"]');
            input.value = Math.max(1, parseInt(input.value || 1) - 1);
        });
    });

    // Add to Cart
    document.querySelectorAll('.add-to-cart').forEach(btn=>{
        btn.addEventListener('click', async ()=>{

            const id = btn.dataset.id;
            const qty = document.querySelector('.qty-input[data-id="'+id+'"]').value || 1;

            try {
                const res = await axios.post('{{ route("transactions.add") }}', {
                    product_id: id,
                    quantity: qty
                });

                // Show success modal
                successMessage.innerText = 'Produk berhasil ditambahkan ke keranjang.';
                successModal.show();

            } catch (err) {
                const message = err?.response?.data?.error || 'Gagal menambahkan produk.';
                
                // Show error modal
                errorMessage.innerText = message;
                errorModal.show();
            }
        });
    });

});
</script>
@endpush

