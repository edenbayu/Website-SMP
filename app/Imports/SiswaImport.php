<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;

class SiswaImport implements ToModel, WithValidation, WithHeadingRow, WithUpserts, WithSkipDuplicates 
{
    /**
     * @param array $row
     *
     * @return Siswa|null
     */

    public function uniqueBy() {
        return 'no_pendaftaran';
    }

    public function rules(): array
    {
        return [
            'no_pendaftaran' => 'unique:siswas,no_pendaftaran',
            'no_un' => 'nullable',
            'no_peserta' => 'nullable',
            'nisn' => 'nullable',
            'no_formulir' => 'nullable',
            'nama' => 'nullable',
            'jenis_kelamin' => 'nullable',
            'tempat_lahir' => 'nullable',
            'tanggal_lahir' => 'nullable',
            'alamat_lengkap' => 'nullable',
            'provinsi' => 'nullable',
            'kota_kabupaten' => 'nullable',
            'kecamatan' => 'nullable',
            'kelurahan' => 'nullable',
            'no_rw' => 'nullable',
            'no_rt' => 'nullable',
            'lintang' => 'nullable',
            'bujur' => 'nullable',
            'asal_sekolah' => 'nullable',
            'npsn_asal_sekolah' => 'nullable',
            'status_kelulusan' => 'nullable',
            'tahun_kelulusan' => 'nullable',
            'kapasitas' => 'nullable',
            'radius' => 'nullable',
            'usia' => 'nullable',
            'waktu_pengajuan' => 'nullable',
            'lokasi_pendaftaran' => 'nullable',
            'lapor_diri' => 'nullable',
            'pilihan_1' => 'nullable',
        ];
    }
    
    public function model(array $row)
    {
        // Return data kosong sebagai null data
        if (!array_filter($row)) {
            return null;
        }

        return new Siswa([
            'no_pendaftaran' => $row['no_pendaftaran'], // Required and unique
            'no_un' => $row['no_un'] ?? null,
            'no_peserta' => $row['no_peserta'] ?? null,
            'nisn' => $row['nisn'] ?? null,
            'no_formulir' => $row['no_formulir'] ?? null,
            'nama' => $row['nama'] ?? null,
            'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
            'alamat_lengkap' => $row['alamat_lengkap'] ?? null,
            'provinsi' => $row['provinsi'] ?? null,
            'kota_kabupaten' => $row['kota_kabupaten'] ?? null,
            'kecamatan' => $row['kecamatan'] ?? null,
            'kelurahan' => $row['kelurahan'] ?? null,
            'no_rw' => $row['no_rw'] ?? null,
            'no_rt' => $row['no_rt'] ?? null,
            'lintang' => $row['lintang'] ?? null,
            'bujur' => $row['bujur'] ?? null,
            'asal_sekolah' => $row['asal_sekolah'] ?? null,
            'npsn_asal_sekolah' => $row['npsn_asal_sekolah'] ?? null,
            'status_kelulusan' => $row['status_kelulusan'] ?? null,
            'tahun_kelulusan' => $row['tahun_kelulusan'] ?? null,
            'kapasitas' => $row['kapasitas'] ?? null,
            'radius' => $row['radius'] ?? null,
            'usia' => $row['usia'] ?? null,
            'waktu_pengajuan' => $row['waktu_pengajuan'] ?? null,
            'lokasi_pendaftaran' => $row['lokasi_pendaftaran'] ?? null,
            'lapor_diri' => $row['lapor_diri'] ?? null,
            'pilihan_1' => $row['pilihan_1'] ?? null,
        ]);
    }

    public function customValidationMessages()
    {
        return [
            'no_pendaftaran.unique' => 'Nomor Pendaftaran :input Telah terdaftar.',
            // Completely change to your desired message format
        ];
    }
}
