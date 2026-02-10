@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">💰 Data Pinjaman</h3>

        @if (auth()->user()->role === 'anggota')
            <a href="{{ route('member.pinjaman.create') }}" class="btn btn-success">
                + Ajukan Pinjaman
            </a>
        @endif
    </div>



    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Anggota</th>
                            <th>Pengajuan</th>
                            <th>Jumlah</th>
                            <th>Tenor</th>
                            <th>Cicilan</th>
                            <th>Status</th>
                            <th>Pengurus</th>
                            <th>Persetujuan</th>

                            @if (auth()->user()->role === 'pengurus')
                                <th width="260">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($pinjamans as $p)
                            <tr>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $p->kode_pinjaman }}
                                    </span>
                                </td>

                                <td class="text-nowrap">
                                    {{ $p->anggota->nama }}
                                </td>

                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('j F Y') }}
                                </td>

                                <td class="fw-bold text-success text-nowrap">
                                    Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                                </td>

                                <td class="text-nowrap">
                                    {{ $p->tenor }} bulan
                                </td>

                                <td class="text-primary fw-semibold text-nowrap">
                                    Rp {{ number_format($p->cicilan, 0, ',', '.') }}
                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @php
                                        $badge = match ($p->status) {
                                            'menunggu' => 'warning',
                                            'disetujui' => 'success',
                                            'ditolak' => 'danger',
                                            'lunas' => 'info',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $badge }}">
                                        {{ ucfirst($p->status) }}
                                    </span>

                                </td>


                                <td class="text-nowrap">
                                    {{ $p->pengurus->nama ?? '-' }}
                                </td>

                                <td class="text-nowrap">
                                    {{ $p->tanggal_persetujuan ? \Carbon\Carbon::parse($p->tanggal_persetujuan)->format('j F Y') : '-' }}
                                </td>



                                {{-- ================= AKSI ================= --}}
                                @if (auth()->user()->role === 'pengurus')
                                    <td class="text-nowrap">

                                        <div class="d-flex-col gap-2 flex-wrap">

                                            {{-- APPROVE --}}
                                            @if ($p->status == 'menunggu')
                                                <button class="btn btn-success btn-sm approve-btn"
                                                    data-id="{{ $p->id }}">
                                                    ✔ Setujui
                                                </button>

                                                <button class="btn btn-danger btn-sm reject-btn"
                                                    data-id="{{ $p->id }}">
                                                    ✖ Tolak
                                                </button>
                                            @endif



                                            {{-- LUNAS --}}
                                            @if ($p->status == 'disetujui')
                                                <button class="btn btn-primary btn-sm paid-btn"
                                                    data-id="{{ $p->id }}">
                                                    ✔ Lunas
                                                </button>
                                            @endif



                                            {{-- DELETE --}}
                                            <button class="btn btn-outline-danger btn-sm delete-btn"
                                                data-id="{{ $p->id }}">
                                                Hapus
                                            </button>

                                        </div>

                                    </td>
                                @endif

                            </tr>

                        @empty

                            <tr>
                                <td colspan="10" class="text-center text-muted">
                                    Tidak ada data pinjaman
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>

            </div>



            <div class="mt-4">
                {{ $pinjamans->links() }}
            </div>

        </div>
    </div>



    {{-- ============================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================= --}}

    <script>
        const csrfToken = '{{ csrf_token() }}';

        function handleAction(url, method, body = null) {
            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: body ? JSON.stringify(body) : null
                })
                .then(() => location.reload());
        }


        // APPROVE
        document.querySelectorAll('.approve-btn').forEach(btn => {
            btn.addEventListener('click', function() {

                if (confirm('Setujui pinjaman ini?')) {
                    handleAction(`/mgmt/pinjaman/${this.dataset.id}`, 'PUT', {
                        status: 'disetujui'
                    });
                }

            });
        });


        // REJECT
        document.querySelectorAll('.reject-btn').forEach(btn => {
            btn.addEventListener('click', function() {

                if (confirm('Tolak pinjaman ini?')) {
                    handleAction(`/mgmt/pinjaman/${this.dataset.id}`, 'PUT', {
                        status: 'ditolak'
                    });
                }

            });
        });


        // LUNAS
        document.querySelectorAll('.paid-btn').forEach(btn => {
            btn.addEventListener('click', function() {

                if (confirm('Tandai pinjaman lunas?')) {
                    handleAction(`/mgmt/pinjaman/${this.dataset.id}`, 'PUT', {
                        status: 'lunas'
                    });
                }

            });
        });


        // DELETE
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {

                if (confirm('Hapus pinjaman ini?')) {
                    handleAction(`/mgmt/pinjaman/${this.dataset.id}`, 'DELETE');
                }

            });
        });
    </script>
@endsection
