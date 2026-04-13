@extends('layouts.app')

@section('title', 'Area Parkir')

@section('content')

<div class="card shadow-lg border-0">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Manajemen Area Parkir</h5>

        <button class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Area
        </button>
    </div>

    <div class="card-body">

        {{-- ✅ ALERT SUCCESS / ERROR --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Nama Area</th>
                        <th>Kapasitas</th>
                        <th>Terisi</th>
                        <th>Sisa Slot</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($area as $a)

                    @php
                        $sisa = $a->kapasitas - $a->terisi;
                        $persen = $a->kapasitas > 0
                            ? ($a->terisi / $a->kapasitas) * 100
                            : 0;
                    @endphp

                    <tr>
                        <td class="fw-semibold">{{ $a->nama_area }}</td>

                        <td>{{ $a->kapasitas }} Slot</td>

                        <td style="width:200px;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-semibold">{{ $a->terisi }}</span>
                                <div class="progress flex-grow-1" style="height:6px;">
                                    <div class="progress-bar bg-info"
                                        style="width: {{ $persen }}%">
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="badge {{ $sisa > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $sisa }} Tersedia
                            </span>
                        </td>

                        <td>
                            <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $a->id_area }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <a href="/area/delete/{{ $a->id_area }}"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Yakin hapus area?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    {{-- MODAL EDIT --}}
                    <div class="modal fade" id="modalEdit{{ $a->id_area }}">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content shadow border-0">

                                <form method="POST" action="/area/update/{{ $a->id_area }}">
                                    @csrf

                                    {{-- ✅ PENANDA MODAL --}}
                                    <input type="hidden" name="_action" value="edit_{{ $a->id_area }}">

                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title">Edit Area</h5>
                                    </div>

                                    <div class="modal-body">

                                        {{-- ✅ ERROR VALIDASI EDIT --}}
                                        @if ($errors->any() && old('_action') == 'edit_' . $a->id_area)
                                            <div class="alert alert-danger">
                                                {{ $errors->first() }}
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label">Nama Area</label>
                                            <input type="text"
                                                   name="nama_area"
                                                   class="form-control"
                                                   value="{{ old('nama_area', $a->nama_area) }}"
                                                   required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Kapasitas</label>
                                            <input type="number"
                                                   name="kapasitas"
                                                   class="form-control"
                                                   value="{{ old('kapasitas', $a->kapasitas) }}"
                                                   required>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">
                                            Simpan Perubahan
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada area parkir.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow border-0">

            <form method="POST" action="/area/store">
                @csrf

                {{-- ✅ PENANDA MODAL --}}
                <input type="hidden" name="_action" value="tambah">

                <div class="modal-header bg-light">
                    <h5 class="modal-title">Tambah Area</h5>
                </div>

                <div class="modal-body">

                    {{-- ✅ ERROR VALIDASI TAMBAH --}}
                    @if ($errors->any() && old('_action') == 'tambah')
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Nama Area</label>
                        <input type="text"
                               name="nama_area"
                               class="form-control"
                               value="{{ old('nama_area') }}"
                               placeholder="Contoh: Basement A"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kapasitas</label>
                        <input type="number"
                               name="kapasitas"
                               class="form-control"
                               value="{{ old('kapasitas') }}"
                               required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Simpan Area
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- ✅ AUTO BUKA MODAL JIKA ADA ERROR --}}
@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const action = "{{ old('_action') }}";
        let modalId = '';

        if (action === 'tambah') {
            modalId = 'modalTambah';
        } else if (action.startsWith('edit_')) {
            modalId = 'modalEdit' + action.replace('edit_', '');
        }

        if (modalId) {
            const modal = new bootstrap.Modal(document.getElementById(modalId));
            modal.show();
        }
    });
</script>
@endif

@endsection