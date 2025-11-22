

<?php $__env->startSection('title','Transaksi - Produk'); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">

    <div class="card p-3">
        <div class="mb-3">
            <input type="text" class="form-control search-input" placeholder="Cari Produk..." id="productSearch">
        </div>

        <div class="toolbar mb-2">
            <h5 class="mb-0">Daftar Produk</h5>
            <div class="text-end">
                <button type="button" class="btn btn-success">
                    <a href="<?php echo e(route('cart.view')); ?>" class="btn btn-cart text-white">
                    <i class="fas fa-shopping-cart me-2"></i> Keranjang
                    <?php if(!empty($cart)): ?>
                        <span class="badge bg-white text-dark ms-2"><?php echo e(count($cart)); ?></span>
                    <?php endif; ?>
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
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr data-name="<?php echo e(strtolower($product->name)); ?>"
    class="<?php echo e($product->stock == 0 ? 'table-secondary opacity-50' : ''); ?>"
    style="<?php echo e($product->stock == 0 ? 'pointer-events:none;' : ''); ?>"
>
    <td><?php echo e($product->code ?? 'PRD-' . str_pad($product->id,3,'0',STR_PAD_LEFT)); ?></td>
    <td><?php echo e($product->name); ?></td>
    <td><?php echo e($product->category->name ?? '-'); ?></td>
    <td>Rp <?php echo e(number_format($product->price,0,',','.')); ?></td>

    <td>
        <div class="qty-control">
            <button class="btn btn-sm btn-outline-secondary btn-decrease"
                data-id="<?php echo e($product->id); ?>"
                <?php echo e($product->stock == 0 ? 'disabled' : ''); ?>

            >&#x2013;</button>

            <input type="number"
                min="1"
                value="1"
                class="form-control form-control-sm qty-input"
                style="width:70px;"
                data-id="<?php echo e($product->id); ?>"
                <?php echo e($product->stock == 0 ? 'disabled' : ''); ?>

            >

            <button class="btn btn-sm btn-outline-secondary btn-increase"
                data-id="<?php echo e($product->id); ?>"
                <?php echo e($product->stock == 0 ? 'disabled' : ''); ?>

            >+</button>
        </div>
    </td>

    <td>
        <?php if($product->stock > 0): ?>
            <button class="btn btn-sm btn-primary add-to-cart text-white"
                data-id="<?php echo e($product->id); ?>">
                <i class="fas fa-cart-plus"></i>
            </button>
        <?php else: ?>
            <button class="btn btn-sm btn-secondary text-white" disabled style="cursor:not-allowed;">
                Out of Stock
            </button>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-2">
            <div>Menampilkan <?php echo e($products->firstItem() ?? 0); ?> dari <?php echo e($products->total() ?? $products->count()); ?> produk</div>
            <div><?php echo e($products->links()); ?></div>
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>';

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
                const res = await axios.post('<?php echo e(route("transactions.add")); ?>', {
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
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\airpay\resources\views/transactions/products.blade.php ENDPATH**/ ?>