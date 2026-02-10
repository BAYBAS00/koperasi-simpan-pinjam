<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Services\PinjamanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PinjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('role:pengurus')
            ->only(['update', 'destroy']);
    }

    /**
     * LIST
     * Pengurus → semua
     * Anggota → milik sendiri
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'pengurus') {

            $pinjamans = Pinjaman::with(['anggota', 'pengurus'])
                ->latest()
                ->paginate(10);

        } else {

            $pinjamans = Pinjaman::with(['pengurus'])
                ->where('id_anggota', $user->anggota->id)
                ->latest()
                ->paginate(10);
        }

        return view('pinjaman.index', compact('pinjamans'));
    }

    /**
     * FORM PENGAJUAN — anggota saja
     */
    public function create()
    {
        abort_unless(Auth::user()->role === 'anggota', 403);

        return view('pinjaman.create');
    }

    /**
     * AJUKAN PINJAMAN
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        abort_unless($user->role === 'anggota', 403);

        $request->validate([
            'jumlah' => 'required|numeric|min:100000',
            'tenor' => 'required|integer|min:1|max:60',
        ]);

        DB::transaction(function () use ($request, $user) {

            Pinjaman::create([
                'id_anggota' => $user->anggota->id,
                'kode_pinjaman' => 'PIN-'.now()->format('Ymd').'-'.Str::upper(Str::random(5)),
                'tanggal_pengajuan' => now(),
                'jumlah' => $request->jumlah,
                'tenor' => $request->tenor,
                'bunga' => 1, // default
                'cicilan' => 0,
                'status' => 'menunggu',
            ]);
        });

        return redirect()
            ->route('dashboard')
            ->with('success', 'Pinjaman berhasil diajukan');
    }

    /**
     * APPROVE / REJECT / LUNAS
     */
    public function update(Request $request, Pinjaman $pinjaman)
    {
        $user = Auth::user();

        abort_unless($user->role === 'pengurus', 403);

        $request->validate([
            'status' => 'required|in:disetujui,ditolak,lunas',
        ]);

        DB::transaction(function () use ($request, $pinjaman, $user) {

            // =============================
            // APPROVE
            // =============================

            if ($request->status === 'disetujui') {

                $bungaDefault = 1; // ⭐ koperasi rule

                $hasil = PinjamanService::hitung(
                    $pinjaman->jumlah,
                    $pinjaman->tenor,
                    $bungaDefault
                );

                $pinjaman->update([
                    'status' => 'disetujui',
                    'bunga' => $bungaDefault,
                    'cicilan' => $hasil['cicilan'],
                    'id_pengurus' => $user->pengurus->id,
                    'tanggal_persetujuan' => now(),
                ]);

                return;
            }

            // =============================
            // REJECT
            // =============================

            if ($request->status === 'ditolak') {

                $pinjaman->update([
                    'status' => 'ditolak',

                    // ⭐ penting!
                    'id_pengurus' => $user->pengurus->id,
                    'tanggal_persetujuan' => now(),
                ]);

                return;
            }

            // =============================
            // FORCE LUNAS
            // =============================

            if ($request->status === 'lunas') {

                $pinjaman->update([
                    'status' => 'lunas',
                ]);
            }
        });

        return response()->json([
            'message' => 'Status pinjaman berhasil diupdate',
        ]);
    }

    /**
     * DETAIL
     */
    public function show(Pinjaman $pinjaman)
    {
        $user = Auth::user();

        // pengurus bebas lihat
        if ($user->role === 'pengurus') {

            return view('pinjaman.show', [
                'pinjaman' => $pinjaman->load(['anggota', 'pengurus', 'angsuran']),
            ]);
        }

        // anggota hanya miliknya
        abort_unless(
            $user->anggota && $user->anggota->id === $pinjaman->id_anggota,
            403
        );

        return view('pinjaman.show', [
            'pinjaman' => $pinjaman->load(['anggota', 'angsuran']),
        ]);
    }

    /**
     * DELETE (dibatasi!)
     */
    public function destroy(Pinjaman $pinjaman)
    {
        abort_unless(Auth::user()->role === 'pengurus', 403);

        // 🔥 jangan hapus pinjaman aktif
        if ($pinjaman->status === 'disetujui') {

            return response()->json([
                'message' => 'Pinjaman aktif tidak boleh dihapus',
            ], 422);
        }

        // jangan hapus kalau sudah ada angsuran
        if ($pinjaman->angsuran()->exists()) {

            return response()->json([
                'message' => 'Pinjaman sudah memiliki angsuran',
            ], 422);
        }

        $pinjaman->delete();

        return response()->json([
            'message' => 'Pinjaman berhasil dihapus',
        ]);
    }
}
