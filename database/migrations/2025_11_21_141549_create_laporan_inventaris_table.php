<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->date('periode_start');
            $table->date('periode_end');
            $table->integer('barang_masuk')->default(0);
            $table->integer('barang_keluar')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_inventaris');
    }
};
