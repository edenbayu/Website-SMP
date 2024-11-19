<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $table = 'siswas';
    protected $fillable = [
        'nama',
        'nis',
        'nisn',
        'tempat_lahir',
        'jenis_kelamin',
        'agama',
        'status_keluarga',
        'anak_ke',
        'alamat_lengkap',
        'no_telepon_rumah',
        'asal_sekolah',
        'tanggal_diterima',
        'jalur_penerimaan',
        'nama_ayah',
        'nama_ibu',
        'alamat_ortu',
        'no_telp_ortu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'nama_wali',
        'alamat_wali',
        'pekerjaan_wali',
        'angkatan'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user'); // Assuming 'id_user' is the foreign key
    }

    public function kelases()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_siswa');
    }

}
