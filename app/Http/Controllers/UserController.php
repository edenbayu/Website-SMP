<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(Request $request) {
        $user = auth()->user();

        if ($user->hasRole('Guru') || $user->hasRole('Wali Kelas')) {
            $data = Guru::where('id_user', $user->id)->first();
        } else if ($user->hasRole('Admin')) {
            $data = Admin::where('id_user', $user->id)->first();
        } else if ($user->hasRole('Siswa')) {
            $data = Siswa::where('id_user', $user->id)->first();
        }

        if ($data) {
            return view('user.profile', ['data' => $data]);
        } else {
            return view('user.profile');
        }
    }

    public function update_picture(Request $request) {
        $user = auth()->user();
    
        // Validate the image (with max size 5 MB)
        $request->validate([
            'image' => 'required|image|mimes:png,jpeg,jpg|max:5120', // Maximum size 5 MB
        ]);
    
        // Handle file upload
        if ($request->hasFile('image')) {
            // Get the image file
            $image = $request->file('image');
    
            // Get the file's contents as binary data
            $imageData = file_get_contents($image->getRealPath());
    
            // Update the user record with the binary image data
            User::where('id', $user->id)->update(['picture' => $imageData]);
        }
    
        // Redirect back to the profile page (or another page)
        return redirect()->route('profile')->with('success', 'Picture updated successfully.');
    }
    
}
