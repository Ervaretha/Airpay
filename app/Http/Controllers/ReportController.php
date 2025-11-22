<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function keuangan(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $query = Transaction::query();
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->get();
        $totalPemasukan = $transactions->sum('total_amount');
        
        return view('reports.keuangan', compact('transactions', 'totalPemasukan', 'startDate', 'endDate'));
    }

    public function inventaris(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $products = Product::with('category')->get();
        
        $transactionItemsQuery = TransactionItem::query();
        
        if ($startDate && $endDate) {
            $transactionItemsQuery->whereHas('transaction', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });
        }
        
        $transactionItems = $transactionItemsQuery->with(['product', 'transaction'])->get();
        
        $itemsSold = $transactionItems->groupBy('product_id')->map(function($items) {
            return $items->sum('quantity');
        });
        
        return view('reports.inventaris', compact('products', 'transactionItems', 'itemsSold', 'startDate', 'endDate'));
    }
}