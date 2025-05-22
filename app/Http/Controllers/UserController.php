<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        // Only fetch users from the current user's organization
        $currentUser = Auth::user();
        $users = User::inOrganization($currentUser->organization_id)->get();

        return Inertia::render('UsersView', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        // Get all available roles
        $roles = Role::all();

        return Inertia::render('CreateUser', [
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
        ]);

        // Remove role from validated data for user creation
        $role = $validated['role'];
        unset($validated['role']);

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        // Add organization ID from the current user
        $validated['organization_id'] = Auth::user()->organization_id;

        // Create user
        $user = User::create($validated);

        // Assign role
        $user->assignRole($role);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        return Inertia::render('EditUser', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $user->roles,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Prepare validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'role' => 'required|string|exists:roles,name',
        ];

        // Only validate password if provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        // Remove role from validated data for user update
        $role = $validated['role'];
        unset($validated['role']);

        // Hash the password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            // Don't update password if not provided
            unset($validated['password']);
        }

        // Update user
        $user->update($validated);

        // Sync role (remove other roles and assign this one)
        $user->syncRoles([$role]);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return Inertia::render('UserDetail', [
            'user' => $user,
            'roles' => $user->roles,
        ]);
    }
}
