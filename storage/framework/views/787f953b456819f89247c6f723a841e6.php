

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Daftar Produk</h5>
            <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Produk
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($product->id); ?></td>
                            <td><?php echo e($product->code); ?></td>
                            <td><?php echo e($product->name); ?></td>
                            <td><?php echo e($product->category->name); ?></td>
                            <td>Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></td>
                            <td><?php echo e($product->stock); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo e(str_replace(' ', '-', $product->status)); ?>">
                                    <?php echo e(ucfirst($product->status)); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('products.edit', $product)); ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>

                                
                                <button type="button" 
                                        class="btn btn-sm btn-danger btn-delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-url="<?php echo e(route('products.destroy', $product)); ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <?php echo e($products->links()); ?>


        </div>
    </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin menghapus produk ini?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>

                <form id="deleteForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">
                        Hapus
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// When delete button is clicked
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    const deleteForm = document.getElementById('deleteForm');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            let url = this.getAttribute('data-url');
            deleteForm.setAttribute('action', url);
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\airpay\resources\views/products/index.blade.php ENDPATH**/ ?>