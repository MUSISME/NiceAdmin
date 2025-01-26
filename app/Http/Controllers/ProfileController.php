<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        // Determine which user to update
        $user = $request->has('id') 
        ? \App\Models\User::findOrFail($request->input('id')) // Admin edit
        : $request->user(); // Self-edit
        
        $validateData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(\App\Models\User::class)->ignore($user->id),
            ],
        ]);

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
            ->with('message', 'Profile update successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
