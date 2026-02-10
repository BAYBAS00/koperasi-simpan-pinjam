<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AngsuranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * LIST
     * Pengurus → semua
     * Anggota → milik sendiri
     */
    public function index()
    {
        $isPengurus = true;
        $pinjamans = [];
        $user = Auth::user();
        $query = Angsuran::with('pinjaman.anggota')
            ->latest();

        // Anggota
        if ($user->role === 'anggota') {
            $pinjamanIds = $user->anggota
                ->pinjaman()
                ->pluck('id');

            $query->whereIn('id_pinjaman', $pinjamanIds);

            $isPengurus = false;
        }

        // Pengurus
        $angsurans = $query->paginate(5);

        $pinjamans = Pinjaman::with('anggota')
            ->where('status', 'disetujui')
            ->get();

        return view('angsuran.index', compact(
            'angsurans',
            'pinjamans',
            'isPengurus'
        ));
    }

    /**
     * STORE — tambah angsuran
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        abort_unless($user->role === 'pengurus', 403);

        $request->validate([
            'id_pinjaman' => 'required|exists:pinjaman,id',
            'jumlah_bayar' => 'required|numeric|min:1000',
        ]);

        try {

            DB::transaction(function () use ($request) {

                $pinjaman = Pinjaman::lockForUpdate()
                    ->findOrFail($request->id_pinjaman);

                // wajib sudah approve
                if ($pinjaman->status !== 'disetujui') {
                    throw new \Exception('Pinjaman belum disetujui');
                }

                $totalHarusBayar = $pinjaman->cicilan * $pinjaman->tenor;

                $totalSudahBayar = Angsuran::where('id_pinjaman', $pinjaman->id)
                    ->sum('jumlah_bayar');

                // overpayment guard
                if (($totalSudahBayar + $request->jumlah_bayar) > $totalHarusBayar) {
                    throw new \Exception('Pembayaran melebihi sisa pinjaman');
                }

                $sisa = $totalHarusBayar - $totalSudahBayar;

                // harus cicilan atau pelunasan
                if (
                    $request->jumlah_bayar != $pinjaman->cicilan
                    && $request->jumlah_bayar != $sisa
                ) {
                    throw new \Exception(
                        'Jumlah harus Rp '.number_format($pinjaman->cicilan, 0, ',', '.')
                    );
                }

                // create angsuran
                Angsuran::create([
                    'id_pinjaman' => $pinjaman->id,
                    'kode_angsuran' => 'ANG-'.now()->format('Ymd').'-'.Str::upper(Str::random(5)),
                    'tanggal_bayar' => now(),
                    'jumlah_bayar' => $request->jumlah_bayar,
                    'status_bayar' => 'lunas',
                ]);

                // cek setelah bayar
                $totalBaru = Angsuran::where('id_pinjaman', $pinjaman->id)
                    ->sum('jumlah_bayar');

                if ($totalBaru >= $totalHarusBayar) {
                    $pinjaman->update([
                        'status' => 'lunas',
                    ]);
                }
            });

        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Angsuran berhasil ditambahkan');
    }

    /**
     * DELETE
     */
    public function destroy(Angsuran $angsuran)
    {
        abort_unless(Auth::user()->role === 'pengurus', 403);

        DB::transaction(function () use ($angsuran) {

            $pinjaman = Pinjaman::lockForUpdate()
                ->findOrFail($angsuran->id_pinjaman);

            $angsuran->delete();

            // cek ulang total
            $totalBayar = Angsuran::where('id_pinjaman', $pinjaman->id)
                ->sum('jumlah_bayar');

            $totalHarusBayar = $pinjaman->cicilan * $pinjaman->tenor;

            if ($totalBayar < $totalHarusBayar) {

                // revert status kalau sebelumnya lunas
                if ($pinjaman->status === 'lunas') {

                    $pinjaman->update([
                        'status' => 'disetujui',
                    ]);
                }
            }
        });

        return response()->noContent();
    }
}
