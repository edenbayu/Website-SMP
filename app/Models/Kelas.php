<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'id_guru',       // Foreign key for Wali Kelas
        'id_semester',   // Foreign key for Semester
    ];

    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'kelas_siswa');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }

    public function mapel()
    {
        return $this->belongsToMany(Kelas::class, 'mapel_kelas');
    }
}

