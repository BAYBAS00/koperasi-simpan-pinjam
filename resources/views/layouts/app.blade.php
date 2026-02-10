<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Koperasi App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .loan-card {
            border-left: 6px solid #0d6efd;
            margin-bottom: 25px;
        }

        .progress {
            height: 22px;
        }

        .progress-bar {
            font-weight: 600;
        }

        .badge-lunas {
            background: #198754;
        }

        .badge-belum {
            background: #ffc107;
            color: #000;
        }

        .nav-link.active {
            background: #0d6efd;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body style="background:#f1f2f6;">

    <div class="d-flex">

        {{-- SIDEBAR --}}
        <div style="width:260px;min-height:100vh;background:#2f3542;" class="text-white p-3">

            <h4 class="mb-4">🏦 Koperasi</h4>

            <ul class="nav flex-column">

                <li class="nav-item">
                    <a href="/dashboard"
                        class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>

                @if (auth()->user()->role === 'pengurus')
                    <li>
                        <a class="nav-link text-white {{ request()->routeIs('mgmt.users.*') ? 'active' : '' }}"
                            href="{{ route('mgmt.users.index') }}">
                            Users
                        </a>
                    </li>

                    <li>
                        <a class="nav-link text-white {{ request()->routeIs('mgmt.pengurus.*') ? 'active' : '' }}"
                            href="{{ route('mgmt.pengurus.index') }}">
                            Pengurus
                        </a>
                    </li>

                    <li>
                        <a class="nav-link text-white {{ request()->routeIs('mgmt.anggota.*') ? 'active' : '' }}"
                            href="{{ route('mgmt.anggota.index') }}">
                            Anggota
                        </a>
                    </li>

                    <li>
                        <a class="nav-link text-white {{ request()->routeIs('mgmt.pinjaman.*') ? 'active' : '' }}"
                            href="{{ route('mgmt.pinjaman.index') }}">
                            Pinjaman
                        </a>
                    </li>

                    <li>
                        <a class="nav-link text-white {{ request()->routeIs('mgmt.angsuran.*') ? 'active' : '' }}"
                            href="{{ route('mgmt.angsuran.index') }}">
                            Angsuran
                        </a>
                    </li>

                    <li>
                        <a class="nav-link text-white {{ request()->routeIs('mgmt.simpanan-master.*') ? 'active' : '' }}"
                            href="{{ route('mgmt.simpanan-master.index') }}">
                            Simpanan Master
                        </a>
                    </li>

                    <li>
                        <a class="nav-link text-white {{ request()->routeIs('mgmt.simpanan-detail.*') ? 'active' : '' }}"
                            href="{{ route('mgmt.simpanan-detail.index') }}">
                            Simpanan Detail
                        </a>
                    </li>
                @else
                    {{-- <li>
                        <a class="nav-link text-white {{ request()->routeIs('member.pinjaman.*') ? 'active' : '' }}"
                            href="{{ route('member.pinjaman.index') }}">
                            Pinjaman
                        </a>
                    </li> --}}

                    <li>
                        <a class="nav-link text-white {{ request()->routeIs('member.angsuran.*') ? 'active' : '' }}"
                            href="{{ route('member.angsuran.index') }}">
                            Angsuran
                        </a>
                    </li>

                    <li>
                        <a class="nav-link text-white {{ request()->routeIs('member.simpanan_master.*') ? 'active' : '' }}"
                            href="{{ route('member.simpanan_master.index') }}">
                            Simpanan Master
                        </a>
                    </li>

                    <li>
                        <a class="nav-link text-white {{ request()->routeIs('member.simpanan_detail.*') ? 'active' : '' }}"
                            href="{{ route('member.simpanan_detail.index') }}">
                            Riwayat Simpanan
                        </a>
                    </li>
                @endif

            </ul>


            <hr>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-danger w-100">Logout</button>
            </form>

        </div>


        {{-- CONTENT --}}
        <div class="flex-grow-1 p-4">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    @yield('content')
                </div>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
