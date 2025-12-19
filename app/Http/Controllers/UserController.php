<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'theme' => 'light'
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function editRights(User $user)
    {
        return view('users.rights', compact('user'));
    }

    public function updateRights(Request $request, User $user)
    {
        $permissions = [
            'menu_access' => $request->menu_access ?? [],
            'can_edit' => $request->has('can_edit'),
            'can_delete' => $request->has('can_delete'),
            'view_amounts' => $request->has('view_amounts'),
        ];

        $user->update(['permissions' => $permissions]);

        return redirect()->route('users.index')->with('success', 'User rights updated.');
    }

    public function updateTheme(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $user->update(['theme' => $request->theme]);
        }
        return back();
    }
}
