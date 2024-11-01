<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $fillable = [
        'nama',
        'guru_id',
        'semester_id',
    ];

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'mapel_kelas');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

}
