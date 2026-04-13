@extends('layouts.app')

@section('title', 'Pengaturan Tarif')

@section('content')

<div class="card shadow-lg border-0">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Pengaturan Tarif Parkir</h5>

        <button class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Tarif
        </button>
    </div>

    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th width="60">No</th>
                        <th>Jenis Kendaraan</th>
                        <th>Tarif / Jam</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($tarif as $index => $t)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>
                            <span class="badge bg-info text-dark px-3 py-2">
                                {{ strtoupper($t->jenis_kendaraan) }}
                            </span>
                        </td>

                        <td class="fw-semibold text-success">
                            Rp {{ number_format($t->tarif_per_jam, 0, ',', '.') }}
                        </td>

                        <td>
                            <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $t->id_tarif }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <a href="/tarif/delete/{{ $t->id_tarif }}"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Yakin hapus tarif?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    {{-- MODAL EDIT --}}
                    <div class="modal fade" id="modalEdit{{ $t->id_tarif }}"
                         @if ($errors->any() && old('_action') == 'edit_' . $t->id_tarif)
                             data-bs-toggle="modal"
                         @endif>
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content shadow border-0">

                                <form method="POST" action="/tarif/update/{{ $t->id_tarif }}">
                                    @csrf

                                    {{-- ✅ PENANDA MODAL MANA YANG SUBMIT --}}
                                    <input type="hidden" name="_action" value="edit_{{ $t->id_tarif }}">

                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title">Edit Tarif</h5>
                                    </div>

                                    <div class="modal-body">

                                        {{-- ✅ ERROR VALIDASI EDIT --}}
                                        @if ($errors->any() && old('_action') == 'edit_' . $t->id_tarif)
                                            <div class="alert alert-danger">
                                                {{ $errors->first() }}
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label">Jenis Kendaraan</label>
                                            <select name="jenis_kendaraan" class="form-control" required>
                                                <option value="motor" {{ $t->jenis_kendaraan == 'motor' ? 'selected' : '' }}>Motor</option>
                                                <option value="mobil" {{ $t->jenis_kendaraan == 'mobil' ? 'selected' : '' }}>Mobil</option>
                                                <option value="lainnya" {{ $t->jenis_kendaraan == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Tarif per Jam</label>
                                            <input type="number"
                                                   name="tarif_per_jam"
                                                   class="form-control"
                                                   value="{{ $t->tarif_per_jam }}"
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
                        <td colspan="4" class="text-center text-muted">
                            Belum ada data tarif.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

        <small class="text-muted">
            Tarif akan otomatis dikalikan dengan lama parkir saat kendaraan keluar.
        </small>

    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow border-0">

            <form method="POST" action="/tarif/store">
                @csrf

                {{-- ✅ PENANDA MODAL MANA YANG SUBMIT --}}
                <input type="hidden" name="_action" value="tambah">

                <div class="modal-header bg-light">
                    <h5 class="modal-title">Tambah Tarif</h5>
                </div>

                <div class="modal-body">

                    {{-- ✅ ERROR VALIDASI TAMBAH --}}
                    @if ($errors->any() && old('_action') == 'tambah')
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tarif per Jam (Rp)</label>
                        <input type="number"
                               name="tarif_per_jam"
                               class="form-control"
                               placeholder="Contoh: 3000"
                               required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Simpan Tarif
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