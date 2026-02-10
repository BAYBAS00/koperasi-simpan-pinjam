<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AnggotaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // hanya pengurus boleh mutasi data
        $this->middleware('role:pengurus')
            ->only(['store', 'update', 'destroy']);
    }

    /**
     * LIST
     */
    public function index()
    {
        $user = Auth::user();

        // 🔥 anggota hanya lihat dirinya
        if ($user->role === 'anggota') {

            $anggota = Anggota::with('user')
                ->where('id_user', $user->id)
                ->paginate(1);

        } else {

            // pengurus
            $anggota = Anggota::with('user')
                ->latest()
                ->paginate(10);
        }

        return view('anggota.index', [
            'anggota' => $anggota,

            // dropdown create hanya untuk pengurus
            'users' => User::where('role', 'anggota')
                ->whereDoesntHave('anggota')
                ->get(),
        ]);
    }

    /**
     * STORE — hanya pengurus
     */
    public function store(Request $request)
    {

        $request->validate([
            'id_user' => 'required|exists:users,id',
            'nama' => 'required|string|max:100',
            'alamat' => 'required',
            'telepon' => 'required',
            'tanggal_lahir' => 'required|date',
        ]);

        // prevent duplicate
        if (Anggota::where('id_user', $request->id_user)->exists()) {

            return back()->with('error', 'User sudah terdaftar sebagai anggota');
        }

        Anggota::create([
            'id_user' => $request->id_user,
            'kode_anggota' => 'ANG-'.now()->format('Ymd').'-'.Str::upper(Str::random(5)),
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tanggal_daftar' => now(),
        ]);

        return redirect()
            ->route('mgmt.anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan');
    }

    /**
     * UPDATE — hanya pengurus
     */
    public function update(Request $request, Anggota $anggota)
    {

        $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required',
            'telepon' => 'required',
            'tanggal_lahir' => 'required|date',
        ]);

        $anggota->update($request->only([
            'nama', 'alamat', 'telepon', 'tanggal_lahir',
        ]));

        return back()->with('success', 'Data anggota berhasil diupdate');
    }

    /**
     * DELETE — hanya pengurus
     */
    public function destroy(Anggota $anggota)
    {

        if ($anggota->pinjaman()->exists()) {

            return back()->with(
                'error',
                'Anggota tidak bisa dihapus karena memiliki pinjaman'
            );
        }

        $anggota->delete();

        return back()->with('success', 'Anggota berhasil dihapus');
    }
}
