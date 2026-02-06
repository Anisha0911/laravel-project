<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
     // USERS LIST
    public function index()
    {
     $users = User::latest()->get();
    return view('admin.users.index', compact('users'));
    }


    // CREATE USER
    public function create()
{
    return view('admin.users.create');
}


    public function edit(User $user)
{
    return view('admin.users.edit', compact('user'));
}

    // STORE USER (THIS IS YOUR LOGIC)
    public function store(Request $request)
{
    $request->validate([
 'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'required|string',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password, // do NOT bcrypt() here because of cast
        'role' => $request->role,
    ]);

 return redirect()->route('admin.users.index')
                     ->with('success', 'User Created Successfully!');
}

public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required'
    ]);

    $user->update($request->only('name', 'email', 'role'));

return redirect()->route('admin.users.index')
                     ->with('success', 'User Updated Successfully!');

}

public function destroy(User $user)
{
    $user->delete();
    return back()->with('success', 'User Deleted Successfully!');
}


}
