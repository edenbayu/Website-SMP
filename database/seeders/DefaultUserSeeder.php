<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super Admin User
        $admin = User::create([
            'name' => 'Satya',
            'email' => 'satya@gmail.com',
            'username' => 'admin',
            'password' => 'admin'
        ]);
        $admin->assignRole('Admin');

        // Creating Admin User
        $wali_kelas = User::create([
            'name' => 'wali',
            'email' => 'wali@gmail.com',
            'username' => 'walikelas',
            'password' => 'walikelas'
        ]);
        $wali_kelas->assignRole('Wali Kelas');

        // Creating Product Manager User
        $guru= User::create([
            'name' => 'guru',
            'email' => 'guru@gmail.com', 
            'username' => 'guru',
            'password' => 'guru'
        ]);
        $guru->assignRole('Guru');

        // Creating Application User
        $user = User::create([
            'name' => 'Safaat',
            'email' => 'safaat@gmail.com', 
            'username' => 'siswa',
            'password' => 'siswa'
        ]);
        $user->assignRole('Siswa');
    }
}