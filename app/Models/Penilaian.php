<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $fillable = [
        'tipe',
        'judul',
        'kktp',
        'keterangan',
        'tp_id',
        'kelas_id'
    ];

    /**
     * Get the related TP model.
     */
    public function tp()
    {
        return $this->belongsTo(TP::class, 'tp_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function penilaian_siswa()
    {
        return $this->hasMany(PenilaianSiswa::class);
    }
}
