<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'category_id', 'price', 'stock', 'status'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            if ($product->stock <= 0) {
                $product->status = 'habis';
            } elseif ($product->stock < 10) {
                $product->status = 'hampir habis';
            } else {
                $product->status = 'tersedia';
            }
        });
    }
}