

<?php $__env->startSection('title','Detail Laporan Keuangan'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card p-3">
        <a href="<?php echo e(route('reports.keuangan.index')); ?>" class="mb-3 d-inline-block text-decoration-none"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
        <h5>Detail Laporan Keuangan</h5>

        <table class="table table-borderless w-50">
            <tr><td>Judul</td><td><?php echo e($laporan->judul); ?></td></tr>
            <tr><td>Periode</td><td><?php echo e($laporan->periode_start->format('d M Y')); ?> - <?php echo e($laporan->periode_end->format('d M Y')); ?></td></tr>
            <tr><td>Pemasukan</td><td>Rp <?php echo e(number_format($laporan->pemasukan,0,',','.')); ?></td></tr>
            <tr><td>Pengeluaran</td><td>Rp <?php echo e(number_format($laporan->pengeluaran,0,',','.')); ?></td></tr>
            <tr><td>Hasil Bersih</td><td>Rp <?php echo e(number_format($laporan->hasil_bersih,0,',','.')); ?></td></tr>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\airpay\resources\views/reports/keuangan/show.blade.php ENDPATH**/ ?>