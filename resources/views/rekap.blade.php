@extends('layouts.app')

@section('title','Rekap Transaksi')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        {{-- FILTER --}}
        <form method="GET" class="row g-3 mb-4">

            <div class="col-md-3">
                <label class="form-label">Pilih Tanggal</label>
                <input type="date" 
                       name="tanggal" 
                       value="{{ $tanggal ?? date('Y-m-d') }}" 
                       class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Jenis Laporan</label>
                <select name="tipe" class="form-select">
                    <option value="harian" {{ ($tipe ?? '') == 'harian' ? 'selected' : '' }}>Harian</option>
                    <option value="bulanan" {{ ($tipe ?? '') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                </select>
            </div>

            <div class="col-md-2 d-grid">
                <label class="form-label invisible">.</label>
                <button class="btn btn-dark">Tampilkan</button>
            </div>

            <div class="col-md-2 d-grid">
                <label class="form-label invisible">.</label>
                <a href="/rekap/cetak?tanggal={{ $tanggal ?? date('Y-m-d') }}&tipe={{ $tipe ?? 'harian' }}"
                   class="btn btn-primary">
                   Cetak
                </a>
            </div>

        </form>

        {{-- JUDUL + PERIODE --}}
        <div class="text-center mb-3">
            <h5 class="fw-bold mb-1">
                LAPORAN {{ strtoupper($tipe ?? 'HARIAN') }}
            </h5>

            <small class="text-muted">
                @if($tipe == 'harian')
                    Tanggal: {{ date('d M Y', strtotime($tanggal)) }}
                @else
                    Bulan: {{ date('F Y', strtotime($tanggal)) }}
                @endif
            </small>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Plat</th>
                        <th>Durasi</th>
                        <th>Biaya</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($data as $d)
                    <tr>
                        <td class="small text-muted">
                            {{ date('d/m H:i', strtotime($d->waktu_masuk)) }}
                        </td>

                        <td class="small text-muted">
                            {{ $d->waktu_keluar 
                                ? date('d/m H:i', strtotime($d->waktu_keluar)) 
                                : '-' }}
                        </td>

                        <td class="fw-semibold">
                            {{ $d->plat_nomor }}
                        </td>

                        <td>
                            {{ $d->durasi_jam }} Jam
                        </td>

                        <td class="text-end text-success fw-semibold">
                            Rp {{ number_format($d->biaya_total,0,',','.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Tidak ada data transaksi
                        </td>
                    </tr>
                @endforelse

                </tbody>

                {{-- TOTAL --}}
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="4" class="text-end">
                            Total Pendapatan
                        </td>
                        <td class="text-end text-success">
                            Rp {{ number_format($total,0,',','.') }}
                        </td>
                    </tr>
                </tfoot>

            </table>
        </div>

    </div>
</div>

@endsection