<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\KeuanganEntry;
use App\Models\InventarisEntry;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')->paginate(10); // or all()
        $cart = session('cart', []);
        return view('transactions.products', compact('products', 'cart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($request->product_id);
        $cart = session('cart', []);

        $id = $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->quantity;
            if ($cart[$id]['quantity'] > $product->stock) {
                return response()->json(['error' => 'Stok tidak mencukupi'], 422);
            }
            $cart[$id]['subtotal'] = $cart[$id]['quantity'] * $cart[$id]['price'];
        } else {
            if ($request->quantity > $product->stock) {
                return response()->json(['error' => 'Stok tidak mencukupi'], 422);
            }
            $cart[$id] = [
                'id' => $product->id,
                'code' => $product->code ?? 'P' . str_pad($product->id, 3, '0', STR_PAD_LEFT),
                'name' => $product->name,
                'category' => $product->category->name ?? '-',
                'price' => (float) $product->price,
                'quantity' => (int) $request->quantity,
                'subtotal' => (int) $request->quantity * (float) $product->price,
            ];
        }

        session(['cart' => $cart]);

        return response()->json(['cart' => $cart]);
    }

    public function removeFromCart($id)
    {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
        }
        return response()->json(['cart' => $cart]);
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session('cart', []);
        if (!isset($cart[$request->id])) {
            return response()->json(['error' => 'Item tidak ditemukan'], 404);
        }

        $product = Product::find($request->id);
        if ($request->quantity > $product->stock) {
            return response()->json(['error' => 'Stok tidak mencukupi'], 422);
        }

        $cart[$request->id]['quantity'] = (int)$request->quantity;
        $cart[$request->id]['subtotal'] = $cart[$request->id]['quantity'] * $cart[$request->id]['price'];

        session(['cart' => $cart]);

        return response()->json(['cart' => $cart]);
    }

    public function cartView()
    {
        $cart = session('cart', []);
        $total = array_sum(array_map(function($i){ return $i['subtotal']; }, $cart));
        return view('transactions.cart', compact('cart', 'total'));
    }

   public function complete(Request $request)
{
    $request->validate([
        'payment_method' => 'required|in:cash,transfer',
        'cash_received' => 'nullable|numeric|min:0'
    ]);

    $cart = session('cart', []);
    if (empty($cart)) {
        return response()->json(['error' => 'Keranjang kosong'], 422);
    }

    $total = array_sum(array_map(fn($i) => $i['subtotal'], $cart));

    $change = 0;
    $cashReceived = null;
    if ($request->payment_method === 'cash') {
        if ($request->cash_received === null) {
            return response()->json(['error' => 'Masukkan uang diterima'], 422);
        }
        $cashReceived = (float)$request->cash_received;
        if ($cashReceived < $total) {
            return response()->json(['error' => 'Uang diterima kurang dari total'], 422);
        }
        $change = $cashReceived - $total;
    }

    DB::beginTransaction();
    try {
        $transaction = Transaction::create([
            'code' => 'TRX-' . strtoupper(Str::random(6)),
            'total_amount' => $total,
            'cash_received' => $cashReceived,
            'change' => $change,
            'payment_method' => $request->payment_method,
            'user_id' => Auth::id() ?? 1 // adjust as needed
        ]);

        foreach ($cart as $c) {
            $item = TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $c['id'],
                'quantity' => $c['quantity'],
                'price' => $c['price'],
                'subtotal' => $c['subtotal'],
            ]);

            InventarisEntry::create([
                'transaction_id' => $transaction->id,
                'product_id' => $c['id'],
                'quantity' => $c['quantity'],
                'direction' => 'keluar',
                'note' => 'Penjualan #'.$transaction->code,
            ]);

            $p = Product::find($c['id']);
            if ($p) {
                $p->stock = max(0, $p->stock - $c['quantity']);
                $p->save();
            }
        }

        KeuanganEntry::create([
            'transaction_id' => $transaction->id,
            'amount' => $total,
            'type' => 'pemasukan',
            'note' => 'Penjualan #'.$transaction->code,
        ]);

        DB::commit();
        session()->forget('cart');

        return response()->json(['transaction' => $transaction]);
    } catch (\Throwable $e) {
        DB::rollBack();
        return response()->json(['error' => 'Gagal menyelesaikan transaksi: '.$e->getMessage()], 500);
    }
}
}
