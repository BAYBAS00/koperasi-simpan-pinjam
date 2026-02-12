@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>💰 Simpanan Master</h3>
    </div>


    {{-- ============================= --}}
    {{-- FORM HANYA UNTUK PENGURUS --}}
    {{-- ============================= --}}

    @if ($isPengurus)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                Buat Rekening Simpanan
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('mgmt.simpanan-master.store') }}">
                    @csrf

                    <div class="row g-2">

                        <div class="col-md-8">
                            <select name="id_anggota" class="form-control" required>
                                <option value="">-- Pilih Anggota --</option>

                                @foreach ($anggotas as $a)
                                    <option value="{{ $a->id }}">
                                        {{ $a->nama }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-4">
                            <button class="btn btn-success w-100">
                                + Buat Rekening
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

            <table class="table table-striped align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>Kode</th>
                        <th>Anggota</th>
                        <th>Saldo</th>

                        @if ($isPengurus)
                            <th width="120">Action</th>
                        @endif
                    </tr>
                </thead>

                <tbody>

                    @forelse($simpanan_masters as $sm)
                        <tr>
                            <td>
                                <strong>{{ $sm->kode_simpanan_master }}</strong>
                            </td>

                            <td>
                                {{ $sm->anggota->nama }}
                            </td>

                            <td>
                                <span class="badge bg-success fs-6">
                                    Rp {{ number_format($sm->saldo, 0, ',', '.') }}
                                </span>
                            </td>

                            @if ($isPengurus)
                                <td>

                                    <form action="{{ route('mgmt.simpanan-master.destroy', $sm->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button onclick="return confirm('Hapus rekening ini?')"
                                            class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>

                                    </form>

                                </td>
                            @endif

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Belum ada rekening simpanan
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

            <div class="mt-3">
                {{ $simpanan_masters->links() }}
            </div>

        </div>
    </div>

@endsection
