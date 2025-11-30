<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('division')->orderBy('name')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $divisions = Division::all();
        return view('admin.users.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,hr,division_manager,employee'],
            'annual_leave_quota' => ['required', 'integer', 'min:0'],
            'division_id' => ['nullable', 'exists:divisions,id'],
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'annual_leave_quota' => $request->annual_leave_quota,
            'division_id' => $request->division_id,
            'join_date' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $divisions = Division::all();
        return view('admin.users.edit', compact('user', 'divisions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin,hr,division_manager,employee'],
            'annual_leave_quota' => ['required', 'integer', 'min:0'],
            'division_id' => ['nullable', 'exists:divisions,id'],
        ]);

        $data = $request->except('password');

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data user diperbarui!');
    }

    public function destroy(User $user)
    {
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}