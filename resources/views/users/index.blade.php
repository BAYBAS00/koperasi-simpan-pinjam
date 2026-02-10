@extends('layouts.app')

@section('content')

<h3>User Management</h3>

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif
<form method="POST" action="{{ route('mgmt.users.store') }}" class="row g-2 mb-4">
    @csrf

    <div class="col-md-3">
        <input class="form-control" name="username" placeholder="Username" required>
    </div>

    <div class="col-md-3">
        <input type="password" class="form-control" name="password" placeholder="Password" required>
    </div>

    <div class="col-md-3">
        <select class="form-control" name="role" required>
            <option value="">Role</option>
            <option value="pengurus">Pengurus</option>
            <option value="anggota">Anggota</option>
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary w-100">Tambah</button>
    </div>

</form>

<table class="table table-striped">
<tr>
    <th>Username</th>
    <th>Role</th>
    <th width="120">Action</th>
</tr>

@foreach($users as $u)
<tr>
    <td>{{ $u->username }}</td>
    <td>{{ $u->role }}</td>
    <td>
                    <form action="{{ route('mgmt.users.destroy', $u->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin akan menghapus?')">Hapus</button>
                    </form>
                </td>
</tr>
@endforeach

</table>

<div class="mt-4">
                {{ $users->links() }}
            </div>

@endsection
