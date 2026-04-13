@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')

<div class="card shadow border-0">

    <div class="card-header bg-white">
        <h5 class="fw-bold mb-0">Log Aktivitas</h5>
    </div>

    <div class="card-body">

        {{-- 🔥 FILTER --}}
        <form method="GET" action="{{ url('/log') }}" class="row g-2 mb-3">

            <div class="col-md-3">
                <label class="small text-muted">Tanggal Awal</label>
                <input type="date" name="tanggal_awal"
                       value="{{ request('tanggal_awal') }}"
                       class="form-control">
            </div>

            <div class="col-md-3">
                <label class="small text-muted">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir"
                       value="{{ request('tanggal_akhir') }}"
                       class="form-control">
            </div>

            <div class="col-md-3">
                <label class="small text-muted">Keyword</label>
                <input type="text" name="keyword"
                       value="{{ request('keyword') }}"
                       placeholder="Cari aktivitas / user..."
                       class="form-control">
            </div>

            <div class="col-md-1 d-grid">
                <label class="small text-white">.</label>
                <button class="btn btn-dark">Filter</button>
            </div>

            <div class="col-md-2 d-grid">
                <label class="small text-white">.</label>
                <a href="{{ url('/log') }}" class="btn btn-secondary">Reset</a>
            </div>

        </form>

        {{-- 🔥 ERROR VALIDASI --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- 🔥 TABLE --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($log as $l)
                    <tr>
                        <td class="small text-muted">
                            {{ \Carbon\Carbon::parse($l->waktu_aktivitas)->format('d M Y H:i') }}
                        </td>

                        <td class="fw-semibold">
                            {{ $l->nama_lengkap }}
                        </td>

                        <td>
                            {{ $l->aktivitas }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            Belum ada aktivitas
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- 🔥 PAGINATION --}}
        <div class="d-flex justify-content-between align-items-center mt-3">

            <small class="text-muted">
                Menampilkan {{ $log->firstItem() ?? 0 }} - {{ $log->lastItem() ?? 0 }}
                dari {{ $log->total() }} data
            </small>

            <div>
                {{ $log->links('pagination::bootstrap-5') }}
            </div>

        </div>

    </div>
</div>

@endsection