@extends('layouts.app')

@section('content')

<div class="container py-3" style="max-width:650px;">

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <h4 class="fw-bold mb-4 text-center">
                Ajukan Pinjaman
            </h4>

            <form action="{{ route('member.pinjaman.store') }}" method="POST">
                @csrf

                {{-- Jumlah --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jumlah Pinjaman</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number"
                               name="jumlah"
                               id="jumlah"
                               class="form-control @error('jumlah') is-invalid @enderror"
                               placeholder="Minimal 100.000"
                               min="100000"
                               step="10000"
                               required>
                    </div>
                    @error('jumlah')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tenor --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tenor</label>
                    <div class="input-group">
                        <input type="number"
                               name="tenor"
                               id="tenor"
                               class="form-control @error('tenor') is-invalid @enderror"
                               placeholder="1 - 60"
                               min="1"
                               max="60"
                               required>
                        <span class="input-group-text">Bulan</span>
                    </div>
                    @error('tenor')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Bunga --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Bunga</label>
                    <div class="input-group">
                        <input type="number" class="form-control" value="1" disabled>
                        <span class="input-group-text">% / bulan</span>
                    </div>
                    <div class="form-text">
                        Bunga flat 1% per bulan.
                    </div>
                </div>

                {{-- Estimasi --}}
                <div class="card bg-light border-0 mb-4">
                    <div class="card-body">

                        <h6 class="fw-bold mb-3">
                            Estimasi Pembayaran
                        </h6>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Bunga</span>
                            <span>Rp <span id="total-bunga">0</span></span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Pembayaran</span>
                            <span class="fw-semibold">
                                Rp <span id="total-bayar">0</span>
                            </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between fs-5 fw-bold">
                            <span>Cicilan / Bulan</span>
                            <span class="text-primary">
                                Rp <span id="cicilan">0</span>
                            </span>
                        </div>

                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        Ajukan Pinjaman
                    </button>

                    <a href="/dashboard" class="btn btn-outline-secondary">
                        Kembali
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function(){

    const jumlah = document.getElementById('jumlah');
    const tenor = document.getElementById('tenor');

    function hitungEstimasi() {

        const jumlahVal = parseFloat(jumlah.value) || 0;
        const tenorVal = parseFloat(tenor.value) || 0;
        const bunga = 1;

        if (jumlahVal > 0 && tenorVal > 0) {

            const totalBunga = jumlahVal * (bunga / 100) * tenorVal;
            const totalBayar = jumlahVal + totalBunga;
            const cicilan = totalBayar / tenorVal;

            document.getElementById('total-bunga').textContent =
                Math.round(totalBunga).toLocaleString('id-ID');

            document.getElementById('total-bayar').textContent =
                Math.round(totalBayar).toLocaleString('id-ID');

            document.getElementById('cicilan').textContent =
                Math.round(cicilan).toLocaleString('id-ID');

        } else {

            document.getElementById('total-bunga').textContent = '0';
            document.getElementById('total-bayar').textContent = '0';
            document.getElementById('cicilan').textContent = '0';
        }
    }

    jumlah.addEventListener('input', hitungEstimasi);
    tenor.addEventListener('input', hitungEstimasi);

    hitungEstimasi();
});
</script>

@endsection
