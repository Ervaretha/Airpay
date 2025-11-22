<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKeuangan;
use App\Models\KeuanganEntry;
use Illuminate\Support\Facades\Auth;

class LaporanKeuanganController extends Controller
{
    public function index()
    {
        $laporans = LaporanKeuangan::latest()->paginate(10);
        return view('reports.keuangan.index', compact('laporans'));
    }

    public function create()
    {
        return view('reports.keuangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'periode_start' => 'required|date',
            'periode_end' => 'required|date|after_or_equal:periode_start'
        ]);

        $pemasukan = KeuanganEntry::whereBetween('created_at', [$request->periode_start.' 00:00:00', $request->periode_end.' 23:59:59'])
                        ->where('type','pemasukan')
                        ->sum('amount');

        $pengeluaran = KeuanganEntry::whereBetween('created_at', [$request->periode_start.' 00:00:00', $request->periode_end.' 23:59:59'])
                        ->where('type','pengeluaran')
                        ->sum('amount');

        $hasil = $pemasukan - $pengeluaran;

        $laporan = LaporanKeuangan::create([
            'judul' => $request->judul,
            'periode_start' => $request->periode_start,
            'periode_end' => $request->periode_end,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'hasil_bersih' => $hasil,
            'created_by' => Auth::id() ?? 1
        ]);

        return redirect()->route('reports.keuangan.index')->with('success','Laporan keuangan dibuat.');
    }

    public function show($id)
    {
        $laporan = LaporanKeuangan::findOrFail($id);
        return view('reports.keuangan.show', compact('laporan'));
    }
}
