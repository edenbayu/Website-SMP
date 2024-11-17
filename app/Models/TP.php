<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TP extends Model
{
    protected $fillable = [
        'nama',
        'keterangan',
        'cp_id'
    ];

    public function cp()
    {
        return $this->belongsTo(CP::class, 'cp_id');
    }
}
