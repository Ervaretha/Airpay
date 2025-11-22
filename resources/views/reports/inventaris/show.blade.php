@extends('layouts.app')

@section('title','Detail Laporan Inventaris')

@section('content')
<div class="container-fluid">
    <div class="card p-3">
        <a href="{{ route('reports.inventaris.index') }}" class="mb-3 d-inline-block text-decoration-none"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
        <h5>Detail Laporan Inventaris</h5>

        <table class="table table-borderless w-50">
            <tr><td>Judul</td><td>{{ $laporan->judul }}</td></tr>
            <tr><td>Periode</td><td>{{ \Carbon\Carbon::parse($laporan->periode_start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($laporan->periode_end)->format('d M Y') }}</td></tr>
            <tr><td>Barang Masuk</td><td>{{ $laporan->barang_masuk }}</td></tr>
            <tr><td>Barang Keluar</td><td>{{ $laporan->barang_keluar }}</td></tr>
        </table>
    </div>
</div>
@endsection
