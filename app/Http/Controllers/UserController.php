<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pengurus')
            ->only(['store', 'update', 'destroy']);
    }

    /**
     * LIST USER
     */
    public function index()
    {
        $user = Auth::user();

        // 🔥 Jika pengurus → lihat semua
        if ($user->role === 'pengurus') {

            $users = User::latest()->paginate(10);

        }
        // 🔥 Jika anggota → hanya dirinya
        else {

            $users = User::where('id', $user->id)->paginate(1);

        }

        return view('users.index', compact('users'));
    }

    /**
     * CREATE → hanya pengurus
     */
    public function store(StoreUserRequest $request)
    {
        abort_unless(Auth::user()->role === 'pengurus', 403);

        User::create($request->validated());

        return redirect()
            ->route('mgmt.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * UPDATE → hanya pengurus
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        abort_unless(Auth::user()->role === 'pengurus', 403);

        $user->update($request->validated());

        return redirect()
            ->route('mgmt.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    /**
     * DELETE → hanya pengurus
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::user()->id) {

            return back()->with('error',
                'Tidak bisa menghapus akun yang sedang digunakan!'
            );
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}
