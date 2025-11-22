

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-money-bill me-2"></i>Laporan Keuangan</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row mb-4">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo e($startDate ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo e($endDate ?? ''); ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="<?php echo e(route('reports.keuangan')); ?>" class="btn btn-secondary">
                        <i class="fas fa-refresh me-2"></i>Reset
                    </a>
                </div>
            </form>

            <div class="alert alert-info">
                <h6>Ringkasan Keuangan</h6>
                <p><strong>Total Pemasukan:</strong> Rp <?php echo e(number_format($totalPemasukan, 0, ',', '.')); ?></p>
                <p><strong>Periode:</strong> 
                    <?php echo e($startDate ?  date('d/m/Y', strtotime($startDate))  : 'Awal'); ?> - 
                    <?php echo e($endDate ?  date('d/m/Y', strtotime($endDate))  : 'Akhir'); ?>

                </p>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Uang Diterima</th>
                            <th>Kembalian</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($transaction->code); ?></td>
                            <td>Rp <?php echo e(number_format($transaction->total_amount, 0, ',', '.')); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($transaction->payment_method == 'cash' ? 'success' : 'primary'); ?>">
                                    <?php echo e(strtoupper($transaction->payment_method)); ?>

                                </span>
                            </td>
                            <td>Rp <?php echo e(number_format($transaction->cash_received ?? 0, 0, ',', '.')); ?></td>
                            <td>Rp <?php echo e(number_format($transaction->change ?? 0, 0, ',', '.')); ?></td>
                            <td><?php echo e($transaction->created_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\airpay\resources\views/reports/keuangan.blade.php ENDPATH**/ ?>