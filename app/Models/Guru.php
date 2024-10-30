<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = [
        'nama',
        'nip',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'status',
        'pangkat_golongan',
        'pendidikan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // public function kelas()
    // {
    //     return $this->belongsTo(Kelas::class, 'id_guru');
    // }
}
