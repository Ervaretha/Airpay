@extends('layouts.app')

@section('title','Keranjang')

@push('styles')
<style>
.card { padding:18px; }
.payment-method .btn { border-radius:20px; font-weight:600; }
.summary-box { background:#f7f7fb; padding:12px; border-radius:8px; }
.action-icon { width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center; border-radius:6px; }
.qty-control { display:flex; gap:6px; align-items:center; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <a href="{{ route('transactions.index') }}" class="mb-3 d-inline-block text-decoration-none"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
        <h5>Keranjang</h5>

        <div class="table-responsive my-3">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th style="width:80px">ID</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th style="width:120px">Harga</th>
                        <th style="width:160px">Jumlah</th>
                        <th style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody id="cartBody">
                    @forelse($cart as $id => $item)
                    <tr data-id="{{ $id }}">
                        <td>{{ $item['code'] ?? 'P' . str_pad($item['id'],3,'0',STR_PAD_LEFT) }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['category'] }}</td>
                        <td>Rp {{ number_format($item['price'],0,',','.') }}</td>
                        <td>
                            <div class="qty-control">
                                <button class="btn btn-sm btn-outline-secondary btn-dec" data-id="{{ $id }}">&#x2013;</button>
                                <input type="number" min="1" value="{{ $item['quantity'] }}" class="form-control form-control-sm qty-input" style="width:80px" data-id="{{ $id }}">
                                <button class="btn btn-sm btn-outline-secondary btn-inc" data-id="{{ $id }}">+</button>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger remove-item" data-id="{{ $id }}"><i class="fas fa-trash"></i></button>
                            <button class="btn btn-sm btn-outline-secondary" disabled><i class="fas fa-pen"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">Keranjang kosong</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="payment-method mb-3">
                    <label class="d-block mb-2">Metode Pembayaran:</label>
                    <button class="btn btn-sm btn-light btn-success me-2" id="payCash">Cash</button>
                    <button class="btn btn-sm btn-light btn-success" id="payTransfer">Transfer</button>
                </div>

                <div id="cashSection" style="display:none;">
                    <div class="mb-2">
                        <label>Total:</label>
                        <div class="summary-box" id="totalBox">Rp {{ number_format($total ?? 0,0,',','.') }}</div>
                    </div>
                    <div class="mb-2">
                        <label>Uang Diterima:</label>
                        <input type="number" min="0" step="0.01" class="form-control" id="cashReceived">
                    </div>
                    <div class="mb-2">
                        <label>Kembalian:</label>
                        <div class="summary-box" id="changeBox">Rp 0</div>
                    </div>
                </div>

                <div id="transferSection" style="display:none;">
                    <div class="mb-2">
                        <label>Total:</label>
                        <div class="summary-box" id="totalBoxT">Rp {{ number_format($total ?? 0,0,',','.') }}</div>
                    </div>
                    <div class="mb-2">
                        <label>No. Rekening:</label>
                        <input type="text" class="form-control" id="transferAcc" placeholder="6382649265">
                    </div>
                </div>

                <div>
                    <button class="btn btn-primary" id="completeBtn">Selesai</button>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-3">
                    <h6>Ringkasan</h6>
                    <div class="d-flex justify-content-between">
                        <div>Subtotal</div>
                        <div id="summarySubtotal">Rp {{ number_format($total ?? 0,0,',','.') }}</div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <div><strong>Total</strong></div>
                        <div><strong id="summaryTotal">Rp {{ number_format($total ?? 0,0,',','.') }}</strong></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Confirm Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Hapus item ini dari keranjang?
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal-->
<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="errorMessage">Terjadi kesalahan.</div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Berhasil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="successMessage">Berhasil.</div>
            <div class="modal-footer">
                <button class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

// Bootstrap modal 
let deleteModal, errorModal, successModal;
let deleteTargetId = null;

function formatRp(number) {
    const n = Math.round(number);
    return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

document.addEventListener('DOMContentLoaded', function(){

    deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    errorModal  = new bootstrap.Modal(document.getElementById('errorModal'));
    successModal = new bootstrap.Modal(document.getElementById('successModal'));

    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');

    let paymentMethod = null;
    let cart = @json($cart);
    let total = {{ $total ?? 0 }};

    const cashSection = document.getElementById('cashSection');
    const trSection = document.getElementById('transferSection');
    const cashReceivedInput = document.getElementById('cashReceived');
    const changeBox = document.getElementById('changeBox');
    const totalBox = document.getElementById('totalBox');
    const totalBoxT = document.getElementById('totalBoxT');
    const summaryTotal = document.getElementById('summaryTotal');

    function updateTotalsUI() {
        total = Object.values(cart).reduce((s,i)=> s + Number(i.subtotal), 0);
        totalBox.textContent = formatRp(total);
        totalBoxT.textContent = formatRp(total);
        summaryTotal.textContent = formatRp(total);
        document.getElementById('summarySubtotal').textContent = formatRp(total);
    }

    updateTotalsUI();

    // Payment Method Handlers
    document.getElementById('payCash').onclick = () => {
        paymentMethod = 'cash';
        cashSection.style.display = '';
        trSection.style.display = 'none';
        event.target.classList.add('btn-primary');
        document.getElementById('payTransfer').classList.remove('btn-primary');
    };

    document.getElementById('payTransfer').onclick = () => {
        paymentMethod = 'transfer';
        trSection.style.display = '';
        cashSection.style.display = 'none';
        event.target.classList.add('btn-primary');
        document.getElementById('payCash').classList.remove('btn-primary');
    };

    // Quantity Handlers
    function attachQtyHandlers(){
        document.querySelectorAll('.btn-inc').forEach(b=>{
            b.onclick = async () => {
                const id = b.dataset.id;
                const input = document.querySelector('.qty-input[data-id="'+id+'"]');
                const newQty = Number(input.value || 1) + 1;
                input.value = newQty;
                updateQtyOnServer(id, newQty);
            };
        });
        document.querySelectorAll('.btn-dec').forEach(b=>{
            b.onclick = async () => {
                const id = b.dataset.id;
                const input = document.querySelector('.qty-input[data-id="'+id+'"]');
                const newQty = Math.max(1, Number(input.value || 1) - 1);
                input.value = newQty;
                updateQtyOnServer(id, newQty);
            };
        });
        document.querySelectorAll('.qty-input').forEach(inp=>{
            inp.onchange = () => {
                const id = inp.dataset.id;
                const qty = Math.max(1, Number(inp.value || 1));
                inp.value = qty;
                updateQtyOnServer(id, qty);
            };
        });
    }

    attachQtyHandlers();

    // Remove Item
    document.querySelectorAll('.remove-item').forEach(btn=>{
        btn.onclick = () => {
            deleteTargetId = btn.dataset.id;
            deleteModal.show();
        };
    });

    document.getElementById('confirmDeleteBtn').onclick = async () => {
        try {
            await axios.delete('{{ url("/transactions/remove-from-cart") }}/' + deleteTargetId);
            const row = document.querySelector(`tr[data-id="${deleteTargetId}"]`);
            if (row) row.remove();
            delete cart[deleteTargetId];
            updateTotalsUI();
            deleteTargetId = null;

            successMessage.textContent = 'Item berhasil dihapus.';
            successModal.show();
        } catch (err) {
            errorMessage.textContent = 'Gagal menghapus item.';
            errorModal.show();
        }
    };

    // Update Quantity on Server
    async function updateQtyOnServer(id, qty) {
        try {
            const res = await axios.post('{{ route("transactions.updateQuantity") }}', {
                id: id,
                quantity: qty
            });
            cart = res.data.cart;
            updateTotalsUI();
        } catch (err) {
            errorMessage.textContent = err?.response?.data?.error || 'Gagal update kuantitas';
            errorModal.show();
        }
    }

    // Cash Change Calculation
    if (cashReceivedInput) {
        cashReceivedInput.addEventListener('input', ()=>{
            const val = Number(cashReceivedInput.value || 0);
            const change = Math.max(0, val - total);
            changeBox.textContent = formatRp(change);
        });
    }

    // Complete Checkout
    document.getElementById('completeBtn').onclick = async () => {
        if (!paymentMethod) {
            errorMessage.textContent = 'Pilih metode pembayaran terlebih dahulu.';
            return errorModal.show();
        }

        const payload = { payment_method: paymentMethod };

        if (paymentMethod === 'cash') {
            payload.cash_received = Number(cashReceivedInput.value || 0);
        } else {
            payload.account = document.getElementById('transferAcc').value || '';
        }

        try {
            const res = await axios.post('{{ route("transactions.complete") }}', payload);

            successMessage.textContent = 'Transaksi selesai: ' + res.data.transaction.code;
            successModal.show();

            successModal._element.addEventListener('hidden.bs.modal', () => {
                window.location.href = '{{ route("transactions.index") }}';
            });

        } catch (err) {
            errorMessage.textContent = err?.response?.data?.error || 'Gagal menyelesaikan transaksi';
            errorModal.show();
        }
    };

});
</script>
@endpush

