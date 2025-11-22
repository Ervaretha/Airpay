

<?php $__env->startSection('title','Laporan Inventaris'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Laporan Inventaris</h5>
            <a href="<?php echo e(route('reports.inventaris.create')); ?>" class="btn btn-sm btn-primary">Pilih Periode</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr><th>ID</th><th>Judul Laporan</th><th>Tanggal</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $laporans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($l->id); ?></td>
                    <td><?php echo e($l->judul); ?></td>
                    <td><?php echo e($l->periode_start->format('d M Y')); ?> - <?php echo e($l->periode_start->format('d M Y')); ?></td>
                    <td><a href="<?php echo e(route('reports.inventaris.show', $l->id)); ?>" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="text-center">Belum ada laporan</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php echo e($laporans->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\airpay\resources\views/reports/inventaris/index.blade.php ENDPATH**/ ?>