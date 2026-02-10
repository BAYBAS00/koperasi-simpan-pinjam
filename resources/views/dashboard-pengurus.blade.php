@extends('layouts.app')

@section('content')

<h3 class="mb-4">Dashboard Pengurus</h3>

<div class="row g-3">

    <div class="col-md-4">
        <div class="card bg-primary text-white p-3">
            Total Users
            <h2>{{ $totalUsers }}</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-success text-white p-3">
            Total Anggota
            <h2>{{ $totalAnggota }}</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-warning text-dark p-3">
            Pinjaman Menunggu
            <h2>{{ $totalPinjamanMenunggu }}</h2>
        </div>
    </div>

</div>

@endsection
