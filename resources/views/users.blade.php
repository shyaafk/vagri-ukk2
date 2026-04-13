@extends('layouts.app')

@section('title', 'Pengaturan Pengguna')

@section('content')



<div class="card shadow-lg border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Pengaturan Pengguna</h5>

        <button class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah User
        </button>
    </div>

    <div class="card-body">

        {{-- FILTER --}}
        <form method="GET" class="row mb-4 align-items-end">

            <div class="col-md-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="">Semua</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Tampilkan</label>
                <select name="per_page" class="form-select">
                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-dark w-100">Filter</button>
            </div>

        </form>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id_user }}</td>
                        <td>{{ $user->nama_lengkap }}</td>
                        <td>{{ $user->username }}</td>

                        {{-- ROLE BADGE --}}
                        <td>
                            <span class="badge
                                @if($user->role == 'admin') bg-primary
                                @elseif($user->role == 'petugas') bg-info
                                @else bg-warning text-dark
                                @endif">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>

                        {{-- STATUS BADGE --}}
                        <td>
                            <span class="badge
                                {{ $user->status == 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td>
                            <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $user->id_user }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <a href="/users/delete/{{ $user->id_user }}"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Yakin hapus user?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    {{-- MODAL EDIT --}}
                    <div class="modal fade" id="modalEdit{{ $user->id_user }}">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content shadow border-0">

                                <form method="POST" action="/users/update/{{ $user->id_user }}">
                                    @csrf

                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title">Edit User</h5>
                                    </div>

                                    <div class="modal-body row g-3">

                                        <div class="col-md-6">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" name="nama_lengkap"
                                                   class="form-control"
                                                   value="{{ $user->nama_lengkap }}" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username"
                                                   class="form-control"
                                                   value="{{ $user->username }}" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Password</label>
                                            <input type="text" name="password"
                                                   class="form-control"
                                                   value="{{ $user->password }}" required>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Role</label>
                                            <select name="role" class="form-select">
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                                <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="aktif" {{ $user->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option value="nonaktif" {{ $user->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                            </select>
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
                        <td colspan="6" class="text-center text-muted">
                            Data tidak ditemukan
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-end">
            {{ $users->links() }}
        </div>

    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow border-0">

            <form method="POST" action="/users/store">
                @csrf

                <div class="modal-header bg-light">
                    <h5 class="modal-title">Tambah User</h5>
                </div>

                <div class="modal-body row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="text" name="password" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Simpan User
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection