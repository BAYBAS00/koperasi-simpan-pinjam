<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    // AUTO HASH PASSWORD (Laravel 10+)
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // RELASI OPSIONAL
    public function pengurus()
    {
        return $this->hasOne(Pengurus::class, 'id_user');
    }

    public function anggota()
    {
        return $this->hasOne(Anggota::class, 'id_user');
    }
}
