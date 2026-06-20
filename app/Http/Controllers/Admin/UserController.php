<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(12);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateUser($request);
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_admin'] = $request->boolean('is_admin');
        $roles = $validated['roles'] ?? [];
        unset($validated['roles']);

        $user = User::create($validated);
        $user->syncRoles($roles);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $this->validateUser($request, $user);
        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_admin'] = $request->boolean('is_admin');
        $roles = $validated['roles'] ?? [];
        unset($validated['roles']);

        $user->update($validated);
        $user->syncRoles($roles);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 422, 'User aktif tidak bisa menghapus akun sendiri.');
        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        $userId = $user?->id ?? 'NULL';
        $passwordRule = $user ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'];

        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,'.$userId],
            'password' => $passwordRule,
            'roles' => ['array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);
    }
}
