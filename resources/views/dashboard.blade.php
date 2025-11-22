@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-6 mt-2 mb-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-boxes fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Barang Tersedia</h5>
                    <h2 class="text-primary">{{ $barangTersedia }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-arrow-down fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Uang Masuk</h5>
                    <h2 class="text-success">Rp {{ number_format($uangMasuk, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- -------------------------------- --}}
        {{-- Riwayat Transaksi --}}
        {{-- -------------------------------- --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Transaksi (5 Terakhir)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Total</th>
                                    <th>Metode</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatTransaksi as $transaction)
                                <tr>
                                    <td>{{ $transaction->code }}</td>
                                    <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->payment_method == 'cash' ? 'success' : 'primary' }}">
                                            {{ strtoupper($transaction->payment_method) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- -------------------------------- --}}
        {{-- Produk Terlaris --}}
        {{-- -------------------------------- --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Produk Terlaris</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Terjual</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produkTerlaris as $row)
<tr>
    <td>{{ $row->product->name }}</td>
    <td>{{ $row->product->category->name ?? '-' }}</td>
    <td>{{ $row->total_sold }} unit</td>
    <td>
        <span class="status-badge status-{{ str_replace(' ', '-', $row->product->status) }}">
            {{ ucfirst($row->product->status) }}
        </span>
    </td>
</tr>
@endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
