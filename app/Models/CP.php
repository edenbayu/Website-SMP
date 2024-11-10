<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CP extends Model
{
    protected $fillable = [
        'nama',
        'keterangan',
        'mapel_id'
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }
}
