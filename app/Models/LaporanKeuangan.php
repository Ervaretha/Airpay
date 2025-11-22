<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanKeuangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul','periode_start','periode_end',
        'pemasukan','pengeluaran','hasil_bersih','created_by'
    ];

    protected $casts = [
        'periode_start' => 'date',
        'periode_end'   => 'date',
    ];
}
