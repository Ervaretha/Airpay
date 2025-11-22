

<?php $__env->startSection('title','Detail Laporan Inventaris'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card p-3">
        <a href="<?php echo e(route('reports.inventaris.index')); ?>" class="mb-3 d-inline-block text-decoration-none"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
        <h5>Detail Laporan Inventaris</h5>

        <table class="table table-borderless w-50">
            <tr><td>Judul</td><td><?php echo e($laporan->judul); ?></td></tr>
            <tr><td>Periode</td><td><?php echo e(\Carbon\Carbon::parse($laporan->periode_start)->format('d M Y')); ?> - <?php echo e(\Carbon\Carbon::parse($laporan->periode_end)->format('d M Y')); ?></td></tr>
            <tr><td>Barang Masuk</td><td><?php echo e($laporan->barang_masuk); ?></td></tr>
            <tr><td>Barang Keluar</td><td><?php echo e($laporan->barang_keluar); ?></td></tr>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\airpay\resources\views/reports/inventaris/show.blade.php ENDPATH**/ ?>