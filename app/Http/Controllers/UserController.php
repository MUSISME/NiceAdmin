<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = \App\Models\User::paginate(10);
        $roles = Role::get();

        return view('users.index', compact('users', 'roles'));
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
        $validateData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', \Illuminate\Validation\Rules\Password::defaults()],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique(\App\Models\User::class),
            ],
        ]);

        // Hash the password before storing
        $validateData['password'] = \Illuminate\Support\Facades\Hash::make($validateData['password']);

        $user = new \App\Models\User;
        $user->fill($validateData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Handle image upload if provided
        if ($request->has('image')) {
            $path = $request->file('image')->store('images', 'public');
            $user->fill([
                'image' => $path
            ])->save();
        } elseif ($request->delete_image == 'true') {
            $user->image = null;
            $user->save();
        }

        if ($request->role) {
            $user->assignRole($request->role);
        }

        return back()
            ->with('status', 'success')
            ->with('message', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\User $user)
    {
        $user->role = $user->getRoleNames()->first();
        $user->image_path = $user->image ? asset('storage/' . $user->image) : asset('niceadmin/assets/img/agent-dummy.webp');
        return response()->json($user);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\User $user)
    {
    
        // You can add additional checks here to prevent deleting the logged-in user, etc.
        if ($user->id !== auth()->id()) {
            $user->delete();
        } else {
            // Handle error if trying to delete the logged-in user
            // return response()->json(['error' => 'Cannot delete your own account'], 400);
            return back()
                ->with('status', 'error')
                ->with('message', 'Cannot delete your own account');
        }
    
        return back()
                ->with('status', 'success')
                ->with('message', 'User deleted successfully');
    }
}
