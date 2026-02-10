@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">👥 Data Anggota</h3>
</div>


{{-- ================= ERROR MESSAGE ================= --}}
@if ($errors->any())
<div class="alert alert-danger shadow-sm">
    <strong>Terjadi kesalahan:</strong>
    <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif



{{-- ================= FORM ================= --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        Tambah Anggota
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('mgmt.anggota.store') }}">
            @csrf

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">User</label>
                    <select name="id_user" class="form-select" required>
                        <option value="">-- Pilih User --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->username }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nama</label>
                    <input 
                        class="form-control"
                        name="nama"
                        placeholder="Masukkan nama"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir</label>
                    <input 
                        type="date"
                        class="form-control"
                        name="tanggal_lahir"
                        required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Alamat</label>
                    <input 
                        class="form-control"
                        name="alamat"
                        placeholder="Masukkan alamat"
                        required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Telepon</label>
                    <input 
                        class="form-control"
                        name="telepon"
                        placeholder="08xxxx">
                </div>

                <div class="col-12">
                    <button class="btn btn-success">
                        + Tambah Anggota
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>




{{-- ================= TABLE ================= --}}
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        List Anggota
    </div>

    <div class="card-body table-responsive">

        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="60">Kode</th>
                    <th>Nama</th>
                    <th>User</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th width="120">Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($anggota as $a)
                <tr>
                    <td>
                        <span class="badge bg-secondary">
                            {{ $a->kode_anggota }}
                        </span>
                    </td>

                    <td>{{ $a->nama }}</td>

                    <td>{{ $a->user?->username ?? '-' }}</td>

                    <td>{{ $a->alamat }}</td>

                    <td>{{ $a->telepon ?? '-' }}</td>

                    <td>
                        <form 
                            action="{{ route('mgmt.anggota.destroy', $a->id) }}" 
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus anggota ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Belum ada data anggota
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

        <div class="mt-3">
            {{ $anggota->links() }}
        </div>

    </div>
</div>

@endsection
