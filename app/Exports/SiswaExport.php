<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SiswaExport implements FromQuery, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return Siswa::query()->select('nama', DB::raw("CONCAT(nis, ' ') as nis"), DB::raw("CONCAT(nisn, ' ') as nisn"), 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'status_keluarga', 'anak_ke', 'alamat_lengkap', 'no_telepon_rumah',  'asal_sekolah', 'tanggal_diterima', 'jalur_penerimaan', 'nama_ayah', 'nama_ibu', 'no_telp_ortu', 'alamat_ortu', 'pekerjaan_ayah', 'pekerjaan_ibu', 'nama_wali', 'alamat_wali',
        'pekerjaan_wali', 'angkatan');
    }

    public function headings() : array
        {
            return [
                'Nama',
                'NIS',
                'NISN',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Jenis Kelamin',
                'Agama',
                'Status Keluarga',
                'Anak Ke',
                'Alamat Lengkap',
                'Telepon Rumah',
                'Asal Sekolah',
                'Tanggal Diterima',
                'Jalur Penerimaan',
                'Nama Ayah',
                'Nama Ibu',
                'No Telp Ortu',
                'Alamat Ortu',
                'Pekerjaan Ayah',
                'Pekerjaan Ibu',
                'Nama Wali',
                'Alamat Wali',
                'Pekerjaan Wali',
                'Angkatan',
            ];
        }

        public function columnFormats(): array
        {
            return [
                'B' => NumberFormat::FORMAT_TEXT,
                'C' => NumberFormat::FORMAT_TEXT,
            ];
        }
}
