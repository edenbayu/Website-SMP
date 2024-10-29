<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $table = 'siswas';
    protected $fillable = [
        'no_pendaftaran',
        'no_un',
        'no_peserta',
        'nisn',
        'no_formulir',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_lengkap',
        'provinsi',
        'kota_kabupaten',
        'kecamatan',
        'kelurahan',
        'no_rw',
        'no_rt',
        'lintang',
        'bujur',
        'asal_sekolah',
        'npsn_asal_sekolah',
        'status_kelulusan',
        'tahun_kelulusan',
        'kapasitas',
        'radius',
        'usia',
        'waktu_pengajuan',
        'lokasi_pendaftaran',
        'lapor_diri',
        'pilihan_1'
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
