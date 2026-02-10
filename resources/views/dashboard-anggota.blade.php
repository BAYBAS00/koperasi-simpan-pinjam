@extends('layouts.app')

@section('content')

<div class="container py-3">

    <h4 class="fw-bold mb-4">
        Dashboard Anggota
    </h4>

    {{-- DATA DIRI --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <h5 class="fw-bold mb-3">
                Data Diri
            </h5>

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="text-muted small">Kode Anggota</div>
                    <div class="fw-semibold">{{ Auth::user()->anggota->kode_anggota }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Nama</div>
                    <div class="fw-semibold">{{ Auth::user()->anggota->nama }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Tanggal Lahir</div>
                    <div class="fw-semibold">
                        {{ Auth::user()->anggota->tanggal_lahir ? \Carbon\Carbon::parse(Auth::user()->anggota->tanggal_lahir)->format('j F Y') : '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Telepon</div>
                    <div class="fw-semibold">{{ Auth::user()->anggota->telepon }}</div>
                </div>

                <div class="col-12">
                    <div class="text-muted small">Alamat</div>
                    <div class="fw-semibold">{{ Auth::user()->anggota->alamat }}</div>
                </div>

            </div>
        </div>
    </div>


    {{-- PINJAMAN --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Pinjaman Saya</h5>

                <a href="{{ route('member.pinjaman.create') }}"
                   class="btn btn-primary btn-sm">
                    Ajukan Pinjaman
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Tenor</th>
                            <th>Cicilan</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($myPinjamans as $p)

                        @php
                            $badge = match($p->status){
                                'disetujui' => 'success',
                                'menunggu' => 'warning',
                                'ditolak' => 'danger',
                                'lunas' => 'primary',
                                default => 'secondary'
                            };
                        @endphp

                        <tr>
                            <td class="fw-semibold">
                                {{ $p->kode_pinjaman ?? '-' }}
                            </td>

                            <td>
                                {{ $p->tanggal_pengajuan ? \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('j M Y') : '-' }}
                            </td>

                            <td class="fw-semibold text-primary">
                                Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                            </td>

                            <td>{{ $p->tenor }} bulan</td>

                            <td>
                                Rp {{ number_format($p->cicilan ?? 0, 0, ',', '.') }}
                            </td>

                            <td>
                                <span class="badge bg-{{ $badge }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('member.pinjaman.show', $p->id) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada pinjaman
                            </td>
                        </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>
    </div>


    {{-- SIMPANAN --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Simpanan Saya</h5>

                <a href="{{ route('member.simpanan_master.index') }}"
                   class="btn btn-success btn-sm">
                    Lihat Detail
                </a>
            </div>

            @forelse($mySimpanan as $sm)

                <div class="bg-light rounded p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">
                            Total Saldo
                        </div>

                        <div class="fw-bold fs-4 text-success">
                            Rp {{ number_format($sm->saldo ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

            @empty

                <div class="text-center text-muted py-4">
                    Tidak ada simpanan
                </div>

            @endforelse

        </div>
    </div>

</div>

@endsection
