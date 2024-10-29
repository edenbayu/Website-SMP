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
        $siswas = Siswa::orderBy('created_at', 'desc')->get();
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
        $siswa->save(); // Save the changes

        return redirect()->route('siswa.index')->with('success', 'User berhasil dibuat dengan username ' . $user->username . ' dan password ' . $password);
    }

    public function generateMultipleUsers(Request $request)
    {
        // Get the selected IDs from the request
        $siswaIds = $request->input('siswa_ids', []);

        if (empty($siswaIds)) {
            return redirect()->route('siswa.index')->with('success', 'No users selected for generation.');
        }

        foreach ($siswaIds as $id) {
            $siswa = Siswa::findOrFail($id);

            // Only generate a user if id_user is not already set
            if (empty($siswa->id_user)) {
                // Generate a random 6-character password
                $password = Str::random(6);

                // Create a new user associated with the Siswa record (no hashing)
                $user = User::create([
                    'name' => $siswa->nama,
                    'username' => $siswa->nisn,
                    'password' => $password, // Directly assign password without hashing
                ]);

                // Assign the 'Siswa' role to the user
                $user->assignRole('Siswa');

                // Link the new user to the Siswa record
                $siswa->id_user = $user->id;
                $siswa->save();
            }
        }

        return redirect()->route('siswa.index')->with('success', 'Users generated successfully for selected records.');
    }

    public function delete($siswaId)
    {
        $siswa = Siswa::findOrFail($siswaId);
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Account deleted successfully');
    }

    public function edit($siswaId)
    {
        $siswa = Siswa::findOrFail($siswaId);
        return view('siswa.index', compact('siswa'));
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
