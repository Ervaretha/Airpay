<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanInventaris;
use App\Models\InventarisEntry;
use Illuminate\Support\Facades\Auth;

class LaporanInventarisController extends Controller
{
    public function index()
    {
        $laporans = LaporanInventaris::latest()->paginate(10);
        return view('reports.inventaris.index', compact('laporans'));
    }

    public function create()
    {
        return view('reports.inventaris.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'periode_start' => 'required|date',
            'periode_end' => 'required|date|after_or_equal:periode_start'
        ]);

        $masuk = InventarisEntry::whereBetween('created_at', [$request->periode_start.' 00:00:00', $request->periode_end.' 23:59:59'])
                    ->where('direction','masuk')
                    ->sum('quantity');

        $keluar = InventarisEntry::whereBetween('created_at', [$request->periode_start.' 00:00:00', $request->periode_end.' 23:59:59'])
                    ->where('direction','keluar')
                    ->sum('quantity');

        $laporan = LaporanInventaris::create([
            'judul' => $request->judul,
            'periode_start' => $request->periode_start,
            'periode_end' => $request->periode_end,
            'barang_masuk' => $masuk,
            'barang_keluar' => $keluar,
            'created_by' => Auth::id() ?? 1
        ]);

        return redirect()->route('reports.inventaris.index')->with('success','Laporan inventaris dibuat.');
    }

    public function show($id)
    {
        $laporan = LaporanInventaris::findOrFail($id);
        return view('reports.inventaris.show', compact('laporan'));
    }
}
