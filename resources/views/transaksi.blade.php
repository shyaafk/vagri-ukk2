@extends('layouts.app')

@section('title','Transaksi')

@section('content')

<div class="card shadow-lg border-0 mb-4">
    <div class="card-body">

        <h6 class="fw-bold text-success mb-3">
            <i class="bi bi-plus-circle"></i> Input Masuk Kendaraan
        </h6>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/transaksi/masuk">
            @csrf

            {{-- PILIH KENDARAAN --}}
            <div class="mb-3">
                <label class="fw-bold">Pilih Kendaraan (Dari Admin)</label>

                <select name="kendaraan" class="form-select select-kendaraan">
                    <option value="">Cari Plat / Pemilik...</option>
                    @foreach($kendaraan as $k)
                        <option value="{{ $k->id_kendaraan }}">
                            {{ $k->plat_nomor }} - {{ $k->pemilik }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="text-center mb-3 text-muted">
                ---------- ATAU INPUT MANUAL ----------
            </div>

            {{-- INPUT MANUAL --}}
            <div class="row g-3">

                <div class="col-md-2">
                    <input type="text" name="plat" class="form-control" placeholder="Plat">
                </div>

                <div class="col-md-2">
                    <input type="text" name="warna" class="form-control" placeholder="Warna">
                </div>

                <div class="col-md-2">
                    <input type="text" name="pemilik" class="form-control" placeholder="Pemilik">
                </div>

                <div class="col-md-2">
                    <select name="jenis" class="form-select">
                        <option value="motor">Motor</option>
                        <option value="mobil">Mobil</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="area" class="form-select">
                        @foreach($area as $a)
                            <option value="{{ $a->id_area }}">
                                {{ $a->nama_area }} (Sisa: {{ $a->kapasitas - $a->terisi }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-success w-100">
                        <i class="bi bi-check-circle"></i> MASUK
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>


{{-- ===================== --}}
{{-- TABLE TRANSAKSI (INI YG ILANG TADI) --}}
{{-- ===================== --}}
<div class="card shadow border-0">
    <div class="card-body table-responsive">

        <div class="mb-3 text-muted small">
            Total Transaksi: <strong>{{ count($transaksi) }}</strong>
        </div>

        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Waktu</th>
                    <th>Plat</th>
                    <th>Pemilik</th>
                    <th>Jenis</th>
                    <th>Area</th>
                    <th>Status</th>
                    <th>Biaya</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($transaksi as $t)
            <tr>
                <td class="small text-muted">{{ $t->waktu_masuk }}</td>
                <td class="fw-semibold">{{ $t->plat_nomor }}</td>
                <td>{{ $t->pemilik }}</td>

                <td>
                    <span class="badge bg-light text-dark">
                        {{ $t->jenis_kendaraan }}
                    </span>
                </td>

                <td>{{ $t->nama_area }}</td>

                <td>
                    @if($t->status == 'parkir')
                        <span class="badge bg-warning text-dark">Parkir</span>
                    @else
                        <span class="badge bg-success">Selesai</span>
                    @endif
                </td>

                <td>
                    @if($t->status == 'selesai')
                        <span class="text-success fw-semibold">
                            Rp {{ number_format($t->biaya_total, 0, ',', '.') }}
                        </span>
                    @else
                        -
                    @endif
                </td>

                <td>
                    @if($t->status == 'parkir')

                        <a href="/transaksi/keluar/{{ $t->id_transaksi }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Kendaraan keluar?')">
                            <i class="bi bi-box-arrow-right"></i>
                        </a>

                        <button class="btn btn-secondary btn-sm" disabled>
                            <i class="bi bi-printer"></i>
                        </button>

                    @else

                        <button class="btn btn-success btn-sm" disabled>
                            <i class="bi bi-check-circle"></i>
                        </button>

                        <a href="/transaksi/cetak/{{ $t->id_transaksi }}"
                           class="btn btn-info btn-sm">
                            <i class="bi bi-printer"></i>
                        </a>

                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-4">
                    Belum ada transaksi.
                </td>
            </tr>
            @endforelse
            </tbody>

        </table>

    </div>
</div>


{{-- SCRIPT SELECT2 --}}
<script>
document.addEventListener("DOMContentLoaded", function() {

    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.select-kendaraan').select2({
            placeholder: "Cari Plat / Pemilik...",
            allowClear: true,
            width: '100%'
        });
    }

});
</script>

@endsection