@extends('layouts.app')

@section('content')

<div class="container py-3">

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Detail Pinjaman</h4>

                <a href="/dashboard" class="btn btn-outline-secondary btn-sm">
                    Kembali
                </a>
            </div>

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="text-muted small">Kode Pinjaman</div>
                    <div class="fw-semibold">{{ $pinjaman->kode_pinjaman ?? '-' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Anggota</div>
                    <div class="fw-semibold">{{ $pinjaman->anggota->nama ?? '-' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Tanggal Pengajuan</div>
                    <div class="fw-semibold">
                        {{ $pinjaman->tanggal_pengajuan ? \Carbon\Carbon::parse($pinjaman->tanggal_pengajuan)->format('j F Y') : '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Pengurus</div>
                    <div class="fw-semibold">
                        {{ $pinjaman->pengurus->nama ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Jumlah Pinjaman</div>
                    <div class="fw-bold fs-5 text-primary">
                        Rp {{ number_format($pinjaman->jumlah, 0, ',', '.') }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Cicilan / Bulan</div>
                    <div class="fw-bold fs-5">
                        Rp {{ number_format($pinjaman->cicilan ?? 0, 0, ',', '.') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Tenor</div>
                    <div class="fw-semibold">{{ $pinjaman->tenor }} bulan</div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Bunga</div>
                    <div class="fw-semibold">{{ $pinjaman->bunga }}%</div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Status</div>

                    @php
                        $badge = match($pinjaman->status){
                            'disetujui' => 'success',
                            'menunggu' => 'warning',
                            'ditolak' => 'danger',
                            'lunas' => 'primary',
                            default => 'secondary'
                        };
                    @endphp

                    <span class="badge bg-{{ $badge }}">
                        {{ ucfirst($pinjaman->status) }}
                    </span>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Tanggal Persetujuan</div>
                    <div class="fw-semibold">
                        {{ $pinjaman->tanggal_persetujuan ? \Carbon\Carbon::parse($pinjaman->tanggal_persetujuan)->format('j F Y') : '-' }}
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- RIWAYAT ANGSURAN --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">
                Riwayat Angsuran
            </h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pinjaman->angsuran as $a)

                        @php
                            $badge = $a->status_bayar == 'lunas'
                                ? 'success'
                                : 'warning';
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $a->kode_angsuran ?? '-' }}</td>
                            <td>
                                {{ $a->tanggal_bayar ? \Carbon\Carbon::parse($a->tanggal_bayar)->format('j F Y') : '-' }}
                            </td>
                            <td class="fw-semibold">
                                Rp {{ number_format($a->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $badge }}">
                                    {{ ucfirst($a->status_bayar) }}
                                </span>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada angsuran
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@endsection
