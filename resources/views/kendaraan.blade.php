@extends('layouts.app')

@section('title','Data Kendaraan')

@section('content')

<div class="card shadow mb-4">
    <div class="card-body">

        <h5 class="mb-3 text-primary">
            <i class="bi bi-plus-circle"></i> Tambah Kendaraan
        </h5>

        <form method="POST" action="/kendaraan/store" class="row g-3">
            @csrf

            <div class="col-md-3">
                <input type="text" name="plat" class="form-control" placeholder="Plat Nomor" required>
            </div>

            <div class="col-md-2">
                <input type="text" name="warna" class="form-control" placeholder="Warna" required>
            </div>

            <div class="col-md-3">
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
                <button class="btn btn-success w-100">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </div>

        </form>

    </div>
</div>


<div class="card shadow">
    <div class="card-body table-responsive">

        <h5 class="mb-3">Daftar Kendaraan</h5>

        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Plat</th>
                    <th>Pemilik</th>
                    <th>Jenis</th>
                    <th>Warna</th>
                    <th>Input Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            @foreach($data as $i => $d)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $d->plat_nomor }}</td>
                    <td>{{ $d->pemilik }}</td>
                    <td>{{ ucfirst($d->jenis_kendaraan) }}</td>
                    <td>{{ $d->warna }}</td>
                    <td>{{ $d->nama_lengkap }}</td>

                    <td>

                        <!-- EDIT -->
                        <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#edit{{ $d->id_kendaraan }}">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <!-- DELETE -->
                        <a href="/kendaraan/delete/{{ $d->id_kendaraan }}"
                           onclick="return confirm('Yakin hapus?')"
                           class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </a>

                    </td>
                </tr>


                <!-- MODAL EDIT -->
                <div class="modal fade" id="edit{{ $d->id_kendaraan }}">
                    <div class="modal-dialog">
                        <form method="POST" action="/kendaraan/update/{{ $d->id_kendaraan }}">
                            @csrf

                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5>Edit Kendaraan</h5>
                                </div>

                                <div class="modal-body">

                                    <input type="text" name="plat" class="form-control mb-2"
                                           value="{{ $d->plat_nomor }}" required>

                                    <input type="text" name="warna" class="form-control mb-2"
                                           value="{{ $d->warna }}" required>

                                    <input type="text" name="pemilik" class="form-control mb-2"
                                           value="{{ $d->pemilik }}">

                                    <select name="jenis" class="form-select">
                                        <option value="motor" {{ $d->jenis_kendaraan=='motor'?'selected':'' }}>Motor</option>
                                        <option value="mobil" {{ $d->jenis_kendaraan=='mobil'?'selected':'' }}>Mobil</option>
                                        <option value="lainnya" {{ $d->jenis_kendaraan=='lainnya'?'selected':'' }}>Lainnya</option>
                                    </select>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>

                            </div>

                        </form>
                    </div>
                </div>

            @endforeach

            </tbody>
        </table>

    </div>
</div>

@endsection