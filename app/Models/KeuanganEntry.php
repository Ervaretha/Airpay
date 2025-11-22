<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeuanganEntry extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id','amount','type','note'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
