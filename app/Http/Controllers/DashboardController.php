<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\KeuanganEntry;
use App\Models\InventarisEntry;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $barangTersedia = Product::where('stock', '>', 0)->count();


        $uangMasuk = KeuanganEntry::where('type', 'pemasukan')->sum('amount');
        if ($uangMasuk == 0) {
            $uangMasuk = Transaction::sum('total_amount');
        }

        $riwayatTransaksi = Transaction::with('items.product', 'user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $produkTerlaris = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get()
            ->map(function($ti) {
                $product = $ti->product()->with('category')->first();
                return (object)[
                    'product' => $product,
                    'total_sold' => (int)$ti->total_sold
                ];
            });

        return view('dashboard', compact(
            'barangTersedia',
            'uangMasuk',
            'riwayatTransaksi',
            'produkTerlaris'
        ));
    }
}
