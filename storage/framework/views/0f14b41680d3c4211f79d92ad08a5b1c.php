

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-6 mt-2 mb-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-boxes fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Barang Tersedia</h5>
                    <h2 class="text-primary"><?php echo e($barangTersedia); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-arrow-down fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Uang Masuk</h5>
                    <h2 class="text-success">Rp <?php echo e(number_format($uangMasuk, 0, ',', '.')); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Transaksi (5 Terakhir)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Total</th>
                                    <th>Metode</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $riwayatTransaksi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($transaction->code); ?></td>
                                    <td>Rp <?php echo e(number_format($transaction->total_amount, 0, ',', '.')); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($transaction->payment_method == 'cash' ? 'success' : 'primary'); ?>">
                                            <?php echo e(strtoupper($transaction->payment_method)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($transaction->created_at->format('d/m/Y H:i')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
        
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Produk Terlaris</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Terjual</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $produkTerlaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
    <td><?php echo e($row->product->name); ?></td>
    <td><?php echo e($row->product->category->name ?? '-'); ?></td>
    <td><?php echo e($row->total_sold); ?> unit</td>
    <td>
        <span class="status-badge status-<?php echo e(str_replace(' ', '-', $row->product->status)); ?>">
            <?php echo e(ucfirst($row->product->status)); ?>

        </span>
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\airpay\resources\views/dashboard.blade.php ENDPATH**/ ?>