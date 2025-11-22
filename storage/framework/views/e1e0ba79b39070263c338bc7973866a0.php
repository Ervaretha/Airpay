

<?php $__env->startSection('title','Keranjang'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.card { padding:18px; }
.payment-method .btn { border-radius:20px; font-weight:600; }
.summary-box { background:#f7f7fb; padding:12px; border-radius:8px; }
.action-icon { width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center; border-radius:6px; }
.qty-control { display:flex; gap:6px; align-items:center; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card">
        <a href="<?php echo e(route('transactions.index')); ?>" class="mb-3 d-inline-block text-decoration-none"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
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
                    <?php $__empty_1 = true; $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr data-id="<?php echo e($id); ?>">
                        <td><?php echo e($item['code'] ?? 'P' . str_pad($item['id'],3,'0',STR_PAD_LEFT)); ?></td>
                        <td><?php echo e($item['name']); ?></td>
                        <td><?php echo e($item['category']); ?></td>
                        <td>Rp <?php echo e(number_format($item['price'],0,',','.')); ?></td>
                        <td>
                            <div class="qty-control">
                                <button class="btn btn-sm btn-outline-secondary btn-dec" data-id="<?php echo e($id); ?>">&#x2013;</button>
                                <input type="number" min="1" value="<?php echo e($item['quantity']); ?>" class="form-control form-control-sm qty-input" style="width:80px" data-id="<?php echo e($id); ?>">
                                <button class="btn btn-sm btn-outline-secondary btn-inc" data-id="<?php echo e($id); ?>">+</button>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger remove-item" data-id="<?php echo e($id); ?>"><i class="fas fa-trash"></i></button>
                            <button class="btn btn-sm btn-outline-secondary" disabled><i class="fas fa-pen"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center">Keranjang kosong</td></tr>
                    <?php endif; ?>
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
                        <div class="summary-box" id="totalBox">Rp <?php echo e(number_format($total ?? 0,0,',','.')); ?></div>
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
                        <div class="summary-box" id="totalBoxT">Rp <?php echo e(number_format($total ?? 0,0,',','.')); ?></div>
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
                        <div id="summarySubtotal">Rp <?php echo e(number_format($total ?? 0,0,',','.')); ?></div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <div><strong>Total</strong></div>
                        <div><strong id="summaryTotal">Rp <?php echo e(number_format($total ?? 0,0,',','.')); ?></strong></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>';

function formatRp(number) {
    const n = Math.round(number);
    return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

document.addEventListener('DOMContentLoaded', function(){

    // initial state
    let paymentMethod = null;
    let cart = <?php echo json_encode($cart, 15, 512) ?>;
    let total = <?php echo e($total ?? 0); ?>;

    const payCashBtn = document.getElementById('payCash');
    const payTransferBtn = document.getElementById('payTransfer');
    const cashSection = document.getElementById('cashSection');
    const trSection = document.getElementById('transferSection');
    const cashReceivedInput = document.getElementById('cashReceived');
    const changeBox = document.getElementById('changeBox');
    const totalBox = document.getElementById('totalBox');
    const totalBoxT = document.getElementById('totalBoxT');
    const summaryTotal = document.getElementById('summaryTotal');
    const completeBtn = document.getElementById('completeBtn');

    function updateTotalsUI() {
        total = Object.values(cart).reduce((s,i)=> s + Number(i.subtotal), 0);
        totalBox.textContent = formatRp(total);
        totalBoxT.textContent = formatRp(total);
        summaryTotal.textContent = formatRp(total);
        document.getElementById('summarySubtotal').textContent = formatRp(total);
    }

    updateTotalsUI();

    // toggle payment
    payCashBtn.addEventListener('click', ()=> {
        paymentMethod = 'cash';
        cashSection.style.display = '';
        trSection.style.display = 'none';
        payCashBtn.classList.add('btn-primary');
        payCashBtn.classList.remove('btn-light');
        payTransferBtn.classList.remove('btn-primary');
        payTransferBtn.classList.add('btn-light');
    });
    payTransferBtn.addEventListener('click', ()=> {
        paymentMethod = 'transfer';
        cashSection.style.display = 'none';
        trSection.style.display = '';
        payTransferBtn.classList.add('btn-primary');
        payTransferBtn.classList.remove('btn-light');
        payCashBtn.classList.remove('btn-primary');
        payCashBtn.classList.add('btn-light');
    });

    // qty inc/dec & input change
    function attachQtyHandlers(){
        document.querySelectorAll('.btn-inc').forEach(b=>{
            b.onclick = async () => {
                const id = b.dataset.id;
                const input = document.querySelector('.qty-input[data-id="'+id+'"]');
                const newQty = Number(input.value || 1) + 1;
                input.value = newQty;
                await updateQtyOnServer(id, newQty);
            };
        });
        document.querySelectorAll('.btn-dec').forEach(b=>{
            b.onclick = async () => {
                const id = b.dataset.id;
                const input = document.querySelector('.qty-input[data-id="'+id+'"]');
                const newQty = Math.max(1, Number(input.value || 1) - 1);
                input.value = newQty;
                await updateQtyOnServer(id, newQty);
            };
        });
        document.querySelectorAll('.qty-input').forEach(inp=>{
            inp.onchange = async () => {
                const id = inp.dataset.id;
                const newQty = Math.max(1, Number(inp.value || 1));
                inp.value = newQty;
                await updateQtyOnServer(id, newQty);
            };
        });
    }

    attachQtyHandlers();

    // remove item
    document.querySelectorAll('.remove-item').forEach(btn=>{
        btn.onclick = async () => {
            if (!confirm('Hapus item ini dari keranjang?')) return;
            const id = btn.dataset.id;
            try {
                await axios.delete('<?php echo e(url('/transactions/remove-from-cart')); ?>/' + id);
                // remove row
                const row = document.querySelector('tr[data-id="'+id+'"]');
                if (row) row.remove();
                delete cart[id];
                updateTotalsUI();
            } catch (err) {
                alert('Gagal menghapus');
            }
        };
    });

    async function updateQtyOnServer(id, qty) {
        try {
            const res = await axios.post('<?php echo e(route("transactions.updateQuantity")); ?>', { id: id, quantity: qty });
            cart = res.data.cart;
            updateTotalsUI();
        } catch (err) {
            alert(err?.response?.data?.error || 'Gagal update kuantitas');
        }
    }

    // cash change calculation
    if (cashReceivedInput) {
        cashReceivedInput.addEventListener('input', ()=>{
            const val = Number(cashReceivedInput.value || 0);
            const change = Math.max(0, val - total);
            changeBox.textContent = formatRp(change);
        });
    }

    // complete checkout
    completeBtn.addEventListener('click', async ()=>{
        if (!paymentMethod) {
            return alert('Pilih metode pembayaran terlebih dahulu');
        }
        const payload = { payment_method: paymentMethod };
        if (paymentMethod === 'cash') {
            const cashVal = Number(cashReceivedInput.value || 0);
            payload.cash_received = cashVal;
        } else {
            payload.account = document.getElementById('transferAcc').value || null;
        }

        try {
            const res = await axios.post('<?php echo e(route("transactions.complete")); ?>', payload);
            alert('Transaksi selesai: ' + (res.data.transaction.code || 'OK'));
            // redirect back to products or clear UI
            window.location.href = '<?php echo e(route("transactions.index")); ?>';
        } catch (err) {
            alert(err?.response?.data?.error || 'Gagal menyelesaikan transaksi');
        }
    });

});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\airpay\resources\views/transactions/cart.blade.php ENDPATH**/ ?>