@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">👔 Data Pengurus</h3>
</div>


{{-- ================= FORM ================= --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        Tambah Pengurus
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('mgmt.pengurus.store') }}">
            @csrf

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Nama</label>
                    <input 
                        name="nama" 
                        class="form-control"
                        placeholder="Masukkan nama"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Telepon</label>
                    <input 
                        name="telepon" 
                        class="form-control"
                        placeholder="08xxxx"
                        required>
                </div>

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

                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea 
                        name="alamat"
                        class="form-control"
                        rows="2"
                        placeholder="Masukkan alamat"
                        required></textarea>
                </div>

                <div class="col-12">
                    <button class="btn btn-success">
                        + Tambah Pengurus
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>



{{-- ================= TABLE ================= --}}
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        List Pengurus
    </div>

    <div class="card-body table-responsive">

        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="50">No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>User</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($pengurus as $p)
                <tr>
                    <td>{{ $pengurus->firstItem() + $loop->index }}</td>
                    <td>
                        <span class="badge bg-secondary">
                            {{ $p->kode_pengurus }}
                        </span>
                    </td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->alamat }}</td>
                    <td>{{ $p->telepon }}</td>
                    <td>{{ $p->user?->username ?? '-' }}</td>
                    <td>

                        <form 
                            action="{{ route('mgmt.pengurus.destroy', $p->id) }}" 
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus pengurus ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger">
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        Belum ada data pengurus
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

        <div class="mt-3">
            {{ $pengurus->links() }}
        </div>

    </div>
</div>

@endsection
