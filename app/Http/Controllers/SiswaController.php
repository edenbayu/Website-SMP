<?php

namespace App\Http\Controllers;

use App\Exports\SiswaExport;
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

    public function export() {
        return Excel::download(new SiswaExport, 'siswa.xlsx', );
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
        $password = substr($siswa->nisn, -6);

        // Create a new user with the required attributes (no hashing)
        $user = User::create([
            'name' => $siswa->nama,  // Use 'name' to match the database field
            'email' => $siswa->nisn.'@gmail.com',
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
            'nama' => 'nullable|string|max:255',
            'nis' => 'nullable|string|max:50|unique:siswas,nis,' . $siswaId,
            'nisn' => 'nullable|string|max:50|unique:siswas,nisn,' . $siswaId,
            'tempat_lahir' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:10',
            'agama' => 'nullable|string|max:50',
            'status_keluarga' => 'nullable|string|max:50',
            'anak_ke' => 'nullable|integer',
            'alamat_lengkap' => 'nullable|string|max:500',
            'no_telepon_rumah' => 'nullable|string|max:15',
            'asal_sekolah' => 'nullable|string|max:255',
            'tanggal_diterima' => 'nullable|date',
            'jalur_penerimaan' => 'nullable|string|max:255',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'alamat_ortu' => 'nullable|string|max:500',
            'no_telp_ortu' => 'nullable|string|max:15',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'nama_wali' => 'nullable|string|max:255',
            'alamat_wali' => 'nullable|string|max:500',
            'pekerjaan_wali' => 'nullable|string|max:255',
            'angkatan' => 'nullable|integer',
        ]);
        
        $siswa = Siswa::findOrFail($siswaId);
        $siswa->update($request->all());

        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil diubah');
    }   

}
