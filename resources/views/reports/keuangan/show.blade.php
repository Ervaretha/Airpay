@extends('layouts.app')

@section('title','Detail Laporan Keuangan')

@section('content')
<div class="container-fluid">
    <div class="card p-3">
        <a href="{{ route('reports.keuangan.index') }}" class="mb-3 d-inline-block text-decoration-none"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
        <h5>Detail Laporan Keuangan</h5>

        <table class="table table-borderless w-50">
            <tr><td>Judul</td><td>{{ $laporan->judul }}</td></tr>
            <tr><td>Periode</td><td>{{ $laporan->periode_start->format('d M Y') }} - {{ $laporan->periode_end->format('d M Y') }}</td></tr>
            <tr><td>Pemasukan</td><td>Rp {{ number_format($laporan->pemasukan,0,',','.') }}</td></tr>
            <tr><td>Pengeluaran</td><td>Rp {{ number_format($laporan->pengeluaran,0,',','.') }}</td></tr>
            <tr><td>Hasil Bersih</td><td>Rp {{ number_format($laporan->hasil_bersih,0,',','.') }}</td></tr>
        </table>
    </div>
</div>
@endsection
