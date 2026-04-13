@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')

<div class="row g-4">

    <div class="col-md-3">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <h6> Pendapatan Hari Ini</h6>
                <h4 class="text-success">
                    Rp {{ number_format($pendapatanHari,0,',','.') }}
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <h6> Pendapatan Bulan Ini</h6>
                <h4 class="text-success">
                    Rp {{ number_format($pendapatanBulan,0,',','.') }}
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <h6> Kendaraan Hari Ini</h6>
                <h4>{{ $kendaraanHari }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <h6> Transaksi Bulan Ini</h6>
                <h4>{{ $transaksiBulan }}</h4>
            </div>
        </div>
    </div>

</div>

@endsection