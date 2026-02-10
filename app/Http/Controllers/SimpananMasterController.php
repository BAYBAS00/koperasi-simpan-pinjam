<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\SimpananMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SimpananMasterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pengurus')
            ->only(['store', 'destroy']);
    }

    /**
     * LIST
     */
    public function index()
    {
        $user = Auth::user();

        // =========================
        // PENGURUS → lihat semua
        // =========================
        if ($user->role === 'pengurus') {

            return view('simpanan_master.index', [
                'simpanan_masters' => SimpananMaster::with('anggota')
                    ->latest()
                    ->paginate(10),

                'anggotas' => Anggota::doesntHave('simpananMaster')->get(),

                'isPengurus' => true,
            ]);
        }

        // =========================
        // ANGGOTA → lihat miliknya
        // =========================
        return view('simpanan_master.index', [
            'simpanan_masters' => SimpananMaster::with('anggota')
                ->where('id_anggota', $user->anggota->id)
                ->paginate(1),

            'anggotas' => [], // kosongkan
            'isPengurus' => false,
        ]);
    }

    /**
     * CREATE
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->role === 'pengurus', 403);

        $request->validate([
            'id_anggota' => 'required|exists:anggotas,id',
        ]);

        if (SimpananMaster::where('id_anggota', $request->id_anggota)->exists()) {
            return back()->with('error', 'Anggota sudah memiliki rekening simpanan');
        }

        DB::transaction(function () use ($request) {

            SimpananMaster::create([
                'id_anggota' => $request->id_anggota,
                'kode_simpanan_master' => 'SIMP-'.now()->format('Ymd').'-'.Str::upper(Str::random(5)),
                'saldo' => 0,
            ]);

        });

        return back()->with('success', 'Rekening simpanan berhasil dibuat');
    }

    /**
     * DELETE
     */
    public function destroy(SimpananMaster $simpananMaster)
    {
        abort_unless(Auth::user()->role === 'pengurus', 403);

        // jangan hapus kalau sudah ada transaksi
        if ($simpananMaster->details()->exists()) {
            return response()->json([
                'message' => 'Rekening memiliki transaksi',
            ], 422);
        }

        $simpananMaster->delete();

        return response()->noContent();
    }
}
