<?php

namespace App\Http\Controllers;

use App\Models\SimpananDetail;
use App\Models\SimpananMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SimpananDetailController extends Controller
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

        // ======================
        // PENGURUS
        // ======================
        if ($user->role === 'pengurus') {

            return view('simpanan_detail.index', [
                'simpanan_details' => SimpananDetail::with('master.anggota')
                    ->latest()
                    ->paginate(10),

                'simpanan_masters' => SimpananMaster::with('anggota')->get(),

                'isPengurus' => true,
            ]);
        }

        // ======================
        // ANGGOTA
        // ======================
        $masterIds = SimpananMaster::where(
            'id_anggota',
            $user->anggota->id
        )->pluck('id');

        return view('simpanan_detail.index', [
            'simpanan_details' => SimpananDetail::with('master.anggota')
                ->whereIn('id_master', $masterIds)
                ->latest()
                ->paginate(10),

            'simpanan_masters' => [],
            'isPengurus' => false,
        ]);
    }

    /**
     * STORE (SUPER SAFE)
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->role === 'pengurus', 403);

        $request->validate([
            'id_master' => 'required|exists:simpanan_master,id',
            'jumlah' => 'required|numeric|min:1000',
            'jenis_transaksi' => 'required|in:menyimpan,penarikan',
        ]);

        try {

            DB::transaction(function () use ($request) {

                $master = SimpananMaster::lockForUpdate()
                    ->findOrFail($request->id_master);

                // 🔥 PENARIKAN GUARD
                if (
                    $request->jenis_transaksi === 'penarikan'
                    && $master->saldo < $request->jumlah
                ) {
                    throw new \Exception('Saldo tidak cukup');
                }

                SimpananDetail::create([
                    'id_master' => $master->id,
                    'jumlah' => $request->jumlah,
                    'tanggal' => now(),
                    'jenis_transaksi' => $request->jenis_transaksi,
                ]);

                // update saldo
                if ($request->jenis_transaksi === 'menyimpan') {

                    $master->increment('saldo', $request->jumlah);

                } else {

                    $master->decrement('saldo', $request->jumlah);
                }
            });

        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Transaksi berhasil');
    }

    /**
     * DELETE (REVERSAL SAFE)
     */
    public function destroy(SimpananDetail $simpananDetail)
    {
        abort_unless(Auth::user()->role === 'pengurus', 403);

        DB::transaction(function () use ($simpananDetail) {

            $master = SimpananMaster::lockForUpdate()
                ->findOrFail($simpananDetail->id_master);

            // reverse saldo
            if ($simpananDetail->jenis_transaksi === 'menyimpan') {

                $master->decrement('saldo', $simpananDetail->jumlah);

            } else {

                $master->increment('saldo', $simpananDetail->jumlah);
            }

            $simpananDetail->delete();
        });

        return response()->noContent();
    }
}
