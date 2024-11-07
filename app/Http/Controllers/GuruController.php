<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use App\Imports\GuruImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\User;

class GuruController extends Controller 
{
    public function index(){
        $gurus = Guru::orderBy('created_at', 'desc')->get();
        return view('guru.index', compact('gurus'));
    }

    public function import(Request $request) {
        $request->validate([
            'file' => 'required|file|max:2048'
        ]);
        Excel::import(new GuruImport, $request->file('file'));

        return redirect()->route('guru.index')->with('success', 'File berhasil diimport!');
    }

    public function create(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50|unique:gurus,nip',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'pangkat_golongan' => 'nullable|string|max:50',
            'pendidikan' => 'nullable|string|max:50',
        ]);

        Guru::create($request->all());
        return redirect()->route('guru.index')->with('success', 'Guru created successfully!');
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50|unique:gurus,nip,'.$id,
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'pangkat_golongan' => 'nullable|string|max:50',
            'pendidikan' => 'nullable|string|max:50',
        ]);

        $guru->update($request->all());
        return redirect()->route('guru.index')->with('success', 'Guru updated successfully!');
    }

    public function destroy($id) {
        $guru = Guru::findOrFail($id);
        $guru->delete();
        return redirect()->route('guru.index')->with('success', 'Guru deleted successfully!');
    }

    public function generateUser(Request $request, $guruId)
    {
        $guru = Guru::findOrFail($guruId);

        $request->validate([
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string',
            'role' => 'required|string'
        ]);

        $user = User::create([
            'name' => $guru->nama,
            'username' => $request->username,
            'password' => $request->password
        ]);
        
        $user->assignRole($request->role);
        $guru->id_user = $user->id;
        $guru->save();
        return redirect()->route('guru.index')->with('success', 'Berhasil membuat akun guru baru.');
    }
}
