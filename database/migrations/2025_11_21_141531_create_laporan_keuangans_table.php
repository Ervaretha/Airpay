<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_keuangans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->date('periode_start');
            $table->date('periode_end');
            $table->decimal('pemasukan', 14, 2)->default(0);
            $table->decimal('pengeluaran', 14, 2)->default(0);
            $table->decimal('hasil_bersih', 14, 2)->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_keuangans');
    }
};
