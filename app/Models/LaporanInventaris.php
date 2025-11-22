<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanInventaris extends Model
{
    use HasFactory;

    protected $table = 'laporan_inventaris';

    protected $fillable = [
        'judul','periode_start','periode_end',
        'barang_masuk','barang_keluar','created_by'
    ];

    protected $casts = [
        'periode_start' => 'date',
        'periode_end'   => 'date',
    ];
}
