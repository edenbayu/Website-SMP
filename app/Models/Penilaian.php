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
    ];

    /**
     * Get the related TP model.
     */
    public function tp()
    {
        return $this->belongsTo(TP::class, 'tp_id');
    }

    public function penilaian_siswa()
    {
        return $this->belongsToMany(Siswa::class, 'penilaian_siswa');
    }
}
