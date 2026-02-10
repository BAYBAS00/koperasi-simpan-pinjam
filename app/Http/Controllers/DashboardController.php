<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Pengurus;
use App\Models\Pinjaman;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'pengurus') {
            return view('dashboard-pengurus', [
                'totalUsers' => User::count(),
                'totalPengurus' => Pengurus::count(),
                'totalAnggota' => Anggota::count(),
                'totalPinjamanMenunggu' => Pinjaman::where('status', 'menunggu')->count(),
                'totalPinjamanDisetujui' => Pinjaman::where('status', 'disetujui')->count(),
                'totalPinjamanLunas' => Pinjaman::where('status', 'lunas')->count(),
            ]);
        } else {
            return view('dashboard-anggota', [
                'myPinjamans' => Auth::user()->anggota->pinjaman()->with('angsuran')->get(),
                'mySimpanan' => Auth::user()->anggota->simpananMaster()->get(),
            ]);
        }
    }
}
