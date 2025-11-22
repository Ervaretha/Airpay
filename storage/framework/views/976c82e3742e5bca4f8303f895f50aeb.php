

<?php $__env->startSection('title','Buat Laporan Inventaris'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card p-3">
        <a href="<?php echo e(route('reports.inventaris.index')); ?>" class="mb-3 d-inline-block text-decoration-none"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
        <h5>Buat Laporan Inventaris</h5>

        <form method="POST" action="<?php echo e(route('reports.inventaris.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-2">
                <label>Judul</label>
                <input type="text" name="judul" class="form-control" value="Laporan Inventaris Bulan <?php echo e(now()->format('F Y')); ?>">
            </div>
            <div class="mb-2">
                <label>Periode Mulai</label>
                <input type="date" name="periode_start" class="form-control" value="<?php echo e(now()->startOfMonth()->format('Y-m-d')); ?>">
            </div>
            <div class="mb-2">
                <label>Periode Akhir</label>
                <input type="date" name="periode_end" class="form-control" value="<?php echo e(now()->endOfMonth()->format('Y-m-d')); ?>">
            </div>

            <button class="btn btn-primary">Buat Laporan</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\airpay\resources\views/reports/inventaris/create.blade.php ENDPATH**/ ?>