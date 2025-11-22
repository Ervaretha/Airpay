@extends('layouts.app')

@section('title','Buat Laporan Inventaris')

@section('content')
<div class="container-fluid">
    <div class="card p-3">
        <a href="{{ route('reports.inventaris.index') }}" class="mb-3 d-inline-block text-decoration-none"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
        <h5>Buat Laporan Inventaris</h5>

        <form method="POST" action="{{ route('reports.inventaris.store') }}">
            @csrf
            <div class="mb-2">
                <label>Judul</label>
                <input type="text" name="judul" class="form-control" value="Laporan Inventaris Bulan {{ now()->format('F Y') }}">
            </div>
            <div class="mb-2">
                <label>Periode Mulai</label>
                <input type="date" name="periode_start" class="form-control" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
            </div>
            <div class="mb-2">
                <label>Periode Akhir</label>
                <input type="date" name="periode_end" class="form-control" value="{{ now()->endOfMonth()->format('Y-m-d') }}">
            </div>

            <button class="btn btn-primary">Buat Laporan</button>
        </form>
    </div>
</div>
@endsection
