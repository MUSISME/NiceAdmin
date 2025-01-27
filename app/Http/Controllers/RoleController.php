<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = \Spatie\Permission\Models\Role::paginate(10);
        $permissions = Permission::get();

        return view('roles.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'permissions' => ['required', 'array'], // Ensure permissions is an array
            'permissions.*' => ['exists:permissions,name'], // Ensure each element exists in the permissions table
        ]);

        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return back()
            ->with('status', 'success')
            ->with('message', 'Role created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(\Spatie\Permission\Models\Role $role)
    {
        $role->permission = $role->permissions;
        return response()->json($role);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \Spatie\Permission\Models\Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'permissions' => ['required', 'array'], // Ensure permissions is an array
            'permissions.*' => ['exists:permissions,name'], // Ensure each element exists in the permissions table
        ]);
        $role->name = $request->name;
        $role->syncPermissions($request->permissions);

        return back()
            ->with('status', 'success')
            ->with('message', 'Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\Spatie\Permission\Models\Role $role)
    {
        $role->delete();

        return back()
            ->with('status', 'success')
            ->with('message', 'Role deleted successfully!');
    }
}
