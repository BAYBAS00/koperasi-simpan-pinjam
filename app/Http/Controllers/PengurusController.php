<?php

namespace App\Http\Controllers;

use App\Models\Pengurus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PengurusController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pengurus']);
    }

    public function index()
    {
        return view('pengurus.index', [
            'pengurus' => Pengurus::with('user')->latest()->paginate(10),

            // hanya user pengurus yang BELUM punya profil
            'users' => User::where('role', 'pengurus')
                ->whereDoesntHave('pengurus')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required',
            'telepon' => 'required',
            'id_user' => 'required|exists:users,id',
        ]);

        // 🔥 PREVENT DUPLICATE
        if (Pengurus::where('id_user', $request->id_user)->exists()) {

            return back()->with('error', 'User sudah terdaftar sebagai pengurus');
        }

        Pengurus::create([
            'kode_pengurus' => 'PGR-'.now()->format('Ymd').'-'.Str::upper(Str::random(5)),
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'id_user' => $request->id_user,
            'tanggal_daftar' => now(),
        ]);

        return redirect()
            ->route('mgmt.pengurus.index')
            ->with('success', 'Pengurus berhasil ditambahkan');
    }

    public function update(Request $request, Pengurus $pengurus)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required',
            'telepon' => 'required',
        ]);

        $pengurus->update($request->only([
            'nama', 'alamat', 'telepon',
        ]));

        return back()->with('success', 'Pengurus berhasil diupdate');
    }

    public function destroy(Pengurus $pengurus)
    {
        if ($pengurus->id_user === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri');
        }

        if ($pengurus->pinjaman()->where('status', 'disetujui')->exists()) {
            return back()->with('error', 'Pengurus masih memiliki pinjaman aktif');
        }

        $pengurus->delete();

        return back()->with('success', 'Pengurus berhasil dihapus');
    }
}
