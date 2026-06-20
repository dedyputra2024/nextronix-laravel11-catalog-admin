<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('permissions')->orderBy('name')->paginate(12);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(fn ($permission) => str($permission->name)->before(' ')->toString());
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRole($request);
        $permissions = $validated['permissions'] ?? [];
        unset($validated['permissions']);

        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);
        $role->syncPermissions($permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function edit(Role $role)
    {
        abort_if($role->name === 'super-admin' && ! auth()->user()->hasRole('super-admin'), 403);
        $permissions = Permission::orderBy('name')->get()->groupBy(fn ($permission) => str($permission->name)->before(' ')->toString());
        $role->load('permissions');
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        abort_if($role->name === 'super-admin' && ! auth()->user()->hasRole('super-admin'), 403);

        $validated = $this->validateRole($request, $role);
        $permissions = $validated['permissions'] ?? [];
        unset($validated['permissions']);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        abort_if(in_array($role->name, ['super-admin', 'customer'], true), 422, 'Role bawaan tidak boleh dihapus.');
        $role->delete();

        return back()->with('success', 'Role berhasil dihapus.');
    }

    private function validateRole(Request $request, ?Role $role = null): array
    {
        $roleId = $role?->id ?? 'NULL';

        return $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name,'.$roleId],
            'permissions' => ['array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);
    }
}
