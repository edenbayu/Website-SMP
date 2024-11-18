<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianEkskul extends Model
{
    protected $fillable = [
        'nilai',
        'mapel_id',
        'siswa_id',
    ];

    /**
     * Define the relationship with the Penilaian model.
     */
    public function mapel()
    {
        return $this->belongsTo(Penilaian::class, 'mapel_id');
    }

    /**
     * Define the relationship with the Siswa model.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
