@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Manajemen Angsuran</h2>

    {{-- ==============================
FORM TAMBAH (PENGURUS)
============================== --}}
    @if ($isPengurus)
        <div class="card mb-4 shadow-sm">
            <div class="card-body">

                <h5>Tambah Angsuran</h5>

                <form method="POST" action="{{ route('mgmt.angsuran.store') }}" class="row g-2">
                    @csrf

                    <div class="col-md-6">
                        <select name="id_pinjaman" class="form-control" required>
                            <option value="">-- Pilih Pinjaman --</option>

                            @foreach ($pinjamans as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->kode_pinjaman }}
                                    - {{ $p->anggota->nama }}
                                    - Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-4">
                        <input type="number" name="jumlah_bayar" class="form-control" placeholder="Jumlah Bayar" required>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">
                            Bayar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif


    {{-- ==============================
GROUP PER PINJAMAN
============================== --}}

    @foreach ($angsurans->groupBy('id_pinjaman') as $pinjamanId => $items)
        @php
            $pinjaman = $items->first()->pinjaman;

            $totalPinjaman = $pinjaman->cicilan * $pinjaman->tenor;
            $totalBayar = $items->sum('jumlah_bayar');

            $sisa = $totalPinjaman - $totalBayar;

            $progress = $totalPinjaman > 0 ? ($totalBayar / $totalPinjaman) * 100 : 0;

            $progress = min($progress, 100);
        @endphp


        <div class="card loan-card shadow-sm">

            <div class="card-body">

                {{-- HEADER --}}
                <div class="d-flex justify-content-between mb-2">

                    <div>
                        <h5>
                            {{ $pinjaman->kode_pinjaman }}
                        </h5>

                        <small>
                            Anggota :
                            <b>{{ $pinjaman->anggota->nama }}</b>
                        </small>
                    </div>

                    <div>
                        @if ($progress >= 100)
                            <span class="badge badge-lunas">
                                LUNAS
                            </span>
                        @else
                            <span class="badge badge-belum">
                                BELUM LUNAS
                            </span>
                        @endif
                    </div>

                </div>


                {{-- PROGRESS --}}
                <div class="progress mb-3">

                    <div class="progress-bar {{ $progress >= 100 ? 'bg-success' : 'bg-warning' }}" role="progressbar"
                        aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"
                        data-width="{{ $progress }}">
                    </div>

                </div>


                {{-- INFO KEUANGAN --}}
                <div class="row text-center mb-3">

                    <div class="col">
                        <b>Total Pinjaman</b><br>
                        Rp {{ number_format($totalPinjaman, 0, ',', '.') }}
                    </div>

                    <div class="col">
                        <b>Sudah Dibayar</b><br>
                        Rp {{ number_format($totalBayar, 0, ',', '.') }}
                    </div>

                    <div class="col">
                        <b>Sisa</b><br>

                        <span class="{{ $sisa <= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format(max($sisa, 0), 0, ',', '.') }}
                        </span>

                    </div>

                </div>



                {{-- TABLE ANGSURAN --}}
                <table class="table table-bordered">

                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Bayar</th>

                            @if ($isPengurus)
                                <th width="120">Action</th>
                            @endif

                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($items as $a)
                            <tr>

                                <td>{{ $a->kode_angsuran }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($a->tanggal_bayar)->format('j F Y') }}
                                </td>

                                <td>
                                    Rp {{ number_format($a->jumlah_bayar, 0, ',', '.') }}
                                </td>

                                @if ($isPengurus)
                                    <td>

                                        <form action="{{ route('mgmt.angsuran.destroy', $a->id) }}" method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus angsuran?')">
                                                Delete
                                            </button>

                                        </form>

                                    </td>
                                @endif

                            </tr>
                        @endforeach

                    </tbody>
                </table>

                $

            </div>
        </div>
    @endforeach

    {{ $angsurans->links() }}
@endsection

<script>
    document.querySelectorAll('.progress-bar')
        .forEach(bar => {

            bar.style.width = bar.dataset.width + '%';

        });
</script>
