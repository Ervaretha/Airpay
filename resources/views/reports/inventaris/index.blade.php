@extends('layouts.app')

@section('title','Laporan Inventaris')

@section('content')
<div class="container-fluid">
    <div class="card p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Laporan Inventaris</h5>
            <a href="{{ route('reports.inventaris.create') }}" class="btn btn-sm btn-primary">Pilih Periode</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr><th>ID</th><th>Judul Laporan</th><th>Tanggal</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($laporans as $l)
                <tr>
                    <td>{{ $l->id }}</td>
                    <td>{{ $l->judul }}</td>
                    <td>{{ $l->periode_start->format('d M Y') }} - {{ $l->periode_start->format('d M Y') }}</td>
                    <td><a href="{{ route('reports.inventaris.show', $l->id) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">Belum ada laporan</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $laporans->links() }}
    </div>
</div>
@endsection
