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
            'nama' => 'nullable',
            'nis' => 'nullable',
            'nisn' => 'nullable',
            'tempat_lahir' => 'nullable',
            'jenis_kelamin' => 'nullable',
            'agama' => 'nullable',
            'status_keluarga' => 'nullable',
            'anak_ke' => 'nullable',
            'alamat_lengkap' => 'nullable',
            'no_telepon_rumah' => 'nullable',
            'asal_sekolah' => 'nullable',
            'tanggal_diterima' => 'nullable',
            'jalur_penerimaan' => 'nullable',
            'nama_ayah' => 'nullable',
            'nama_ibu' => 'nullable',
            'alamat_ortu' => 'nullable',
            'no_telp_ortu' => 'nullable',
            'pekerjaan_ayah' => 'nullable',
            'pekerjaan_ibu' => 'nullable',
            'nama_wali' => 'nullable',
            'alamat_wali' => 'nullable',
            'pekerjaan_wali' => 'nullable',
            'angkatan' => 'nullable',
        ];
    }
    
    public function model(array $row)
    {
        // Return data kosong sebagai null data
        if (!array_filter($row)) {
            return null;
        }

        return new Siswa([
            'nama' => $row['nama'] ?? null,
            'nis' => $row['nis'] ?? null,
            'nisn' => $row['nisn'] ?? null,
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
            'agama' => $row['agama'] ?? null,
            'status_keluarga' => $row['status_keluarga'] ?? null,
            'anak_ke' => $row['anak_ke'] ?? null,
            'alamat_lengkap' => $row['alamat_lengkap'] ?? null,
            'no_telepon_rumah' => $row['no_telepon_rumah'] ?? null,
            'asal_sekolah' => $row['asal_sekolah'] ?? null,
            'tanggal_diterima' => $row['tanggal_diterima'] ?? null,
            'jalur_penerimaan' => $row['jalur_penerimaan'] ?? null,
            'nama_ayah' => $row['nama_ayah'] ?? null,
            'nama_ibu' => $row['nama_ibu'] ?? null,
            'alamat_ortu' => $row['alamat_ortu'] ?? null,
            'no_telp_ortu' => $row['no_telp_ortu'] ?? null,
            'pekerjaan_ayah' => $row['pekerjaan_ayah'] ?? null,
            'pekerjaan_ibu' => $row['pekerjaan_ibu'] ?? null,
            'nama_wali' => $row['nama_wali'] ?? null,
            'alamat_wali' => $row['alamat_wali'] ?? null,
            'pekerjaan_wali' => $row['pekerjaan_wali'] ?? null,
            'angkatan' => $row['angkatan'] ?? null,
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
