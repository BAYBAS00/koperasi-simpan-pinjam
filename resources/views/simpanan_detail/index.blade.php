@extends('layouts.app')

@section('content')

    @if (!$isPengurus)
    <h3 class="mb-4">📊 Riwayat Simpanan</h3>
    @else
    <h3 class="mb-4">📊 Simpanan Detail</h3>
    @endif


    {{-- ============================= --}}
    {{-- FORM PENGURUS --}}
    {{-- ============================= --}}

    @if ($isPengurus)
        <div class="card mb-4 shadow-sm">

            <div class="card-header bg-primary text-white">
                Tambah Transaksi
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('mgmt.simpanan-detail.store') }}">
                    @csrf

                    <div class="row g-2">

                        <div class="col-md-4">
                            <select name="id_master" class="form-control" required>
                                <option value="">-- Pilih Rekening --</option>

                                @foreach ($simpanan_masters as $sm)
                                    <option value="{{ $sm->id }}">
                                        {{ $sm->kode_simpanan_master }}
                                       - {{ $sm->anggota->nama }}
                                       - (Saldo: Rp {{ number_format($sm->saldo, 0, ',', '.') }})
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-3">
                            <input type="number" name="jumlah" class="form-control" placeholder="Jumlah" required>
                        </div>

                        <div class="col-md-3">
                            <select name="jenis_transaksi" class="form-control">

                                <option value="menyimpan">
                                    Menyimpan
                                </option>

                                <option value="penarikan">
                                    Penarikan
                                </option>

                            </select>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-success w-100">
                                + Tambah
                            </button>
                        </div>

                    </div>

                </form>

            </div>
        </div>
    @endif


    {{-- ============================= --}}
    {{-- TABLE --}}
    {{-- ============================= --}}

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-bordered align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Anggota</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>

                        @if ($isPengurus)
                            <th width="120">Action</th>
                        @endif
                    </tr>
                </thead>

                <tbody>

                    @forelse($simpanan_details as $sd)
                        <tr>

                            <td>
                                {{ \Carbon\Carbon::parse($sd->tanggal)->format('j F Y') }}
                            </td>

                            <td>
                                {{ $sd->master->anggota->nama }}
                            </td>

                            <td>

                                @if ($sd->jenis_transaksi == 'menyimpan')
                                    <span class="badge bg-success">
                                        Menabung
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Tarik
                                    </span>
                                @endif

                            </td>

                            <td>
                                <strong>
                                    Rp {{ number_format($sd->jumlah, 0, ',', '.') }}
                                </strong>
                            </td>

                            @if ($isPengurus)
                                <td>

                                    <form method="POST" action="{{ route('mgmt.simpanan-detail.destroy', $sd->id) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button onclick="return confirm('Hapus transaksi?')" class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>

                                    </form>

                                </td>
                            @endif

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Belum ada transaksi
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

            <div class="mt-3">
                {{ $simpanan_details->links() }}
            </div>

        </div>
    </div>

@endsection
