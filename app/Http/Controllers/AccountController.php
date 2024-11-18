<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AccountController extends Controller
{
    public function index(Request $request){
        $accounts = User::all();

        return view('account.index', compact('accounts'));
    }

    public function edit($id) {
        $account = User::findOrFail($id);
        $roles = Role::all(); // Get all available roles
    
        return view('account.edit', compact('account', 'roles'));
    }
    

    public function destroy($id){
        $account = User::findOrFail($id);
        $account->delete();

        return redirect()->route('account.index')->with('success', 'Account deleted successfully');
    }

    public function update(Request $request, $id) {
        $account = User::findOrFail($id);
    
        // Update the user's basic info
        $account->name = $request->input('name');
        $account->email = $request->input('email');
        $account->username = $request->input('username');
        $account->password = $request->input('password'); // Not hashed
        $account->save();
    
        // Update the user's role
        $account->syncRoles($request->input('role')); // Use syncRoles to replace the current role with the new one
    
        return redirect()->route('account.index')->with('success', 'Account and role updated successfully');
    }
}
