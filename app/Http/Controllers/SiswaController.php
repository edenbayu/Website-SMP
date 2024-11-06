<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\User;

class SiswaController extends Controller 
{
    public function index(){
        $siswas = Siswa::all();
        return view('siswa.index', compact('siswas'));
    }

    public function import(Request $request) {
        $request->validate([
            'file' => 'required|max:2048'
        ]);
        Excel::import(new SiswaImport, $request->file('file'));
        
        return redirect()->route('siswa.index')->with('success', 'File berhasil diimport!');
    }

    public function showImportForm()
    {
        return view('siswa.import');
    }

    public function generateUser($siswaId)
    {
        // Find the `Siswa` record
        $siswa = Siswa::findOrFail($siswaId);

        // Generate a random 6-digit password
        $password = Str::random(6);

        // Create a new user with the required attributes (no hashing)
        $user = User::create([
            'name' => $siswa->nama,  // Use 'name' to match the database field
            'username' => $siswa->nisn,
            'password' => $password,  // Directly assign password without hashing
        ]);

        // Automatically assign the 'Siswa' role to the new user
        $user->assignRole('Siswa');

        // Update `Siswa` to reference the new user's ID
        $siswa->id_user = $user->id; // Set the new id_user
        $siswa->nis = $siswa->id; // Set user nis same as siswa's id
        $siswa->save(); // Save the changes

        return redirect()->route('siswa.index')->with('success', 'User berhasil dibuat dengan username ' . $user->username . ' dan password ' . $password);
    }

    public function delete($siswaId)
    {
        $siswa = Siswa::findOrFail($siswaId);
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Account deleted successfully');
    }
    
    public function update(Request $request, $siswaId)
    {
        // Validate that only `no_pendaftaran` is required and all other fields are nullable
        $request->validate([
            'no_pendaftaran' => 'required|string|max:255',
            'no_un' => 'nullable|string|max:255',
            'no_peserta' => 'nullable|string|max:255',
            'nisn' => 'nullable|string|max:50|unique:siswas,nisn,' . $siswaId,
            'no_formulir' => 'nullable|string|max:255',
            'nama' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:10',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat_lengkap' => 'nullable|string|max:500',
            'provinsi' => 'nullable|string|max:100',
            'kota_kabupaten' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'no_rw' => 'nullable|string|max:10',
            'no_rt' => 'nullable|string|max:10',
            'lintang' => 'nullable|numeric',
            'bujur' => 'nullable|numeric',
            'asal_sekolah' => 'nullable|string|max:255',
            'npsn_asal_sekolah' => 'nullable|string|max:50',
            'status_kelulusan' => 'nullable|string|max:50',
            'tahun_kelulusan' => 'nullable|integer',
            'kapasitas' => 'nullable|integer',
            'radius' => 'nullable|integer',
            'usia' => 'nullable|integer',
            'waktu_pengajuan' => 'nullable|date',
            'lokasi_pendaftaran' => 'nullable|string|max:255',
            'lapor_diri' => 'nullable|string|max:50',
            'pilihan_1' => 'nullable|string|max:255',
        ]);

        $siswa = Siswa::findOrFail($siswaId);
        $siswa->update($request->all());

        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil diubah');
    }   

}
