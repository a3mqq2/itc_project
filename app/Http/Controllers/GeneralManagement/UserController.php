<?php

namespace App\Http\Controllers\GeneralManagement;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(10);
        return view('general-management.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('general-management.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->roles);

        return redirect()->route('general-management.users.index')
            ->with('success', __('messages.user_created_successfully'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('general-management.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles($request->roles);

        return redirect()->route('general-management.users.index')
            ->with('success', __('messages.user_updated_successfully'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('general-management.users.index')
            ->with('success', __('messages.user_deleted_successfully'));
    }
}
