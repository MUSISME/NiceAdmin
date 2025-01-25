<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'confirmed', // Ensures password matches password_confirmation
                Password::min(8)
                    // ->letters()
                    // ->numbers()
                    // ->mixedCase()
                    // ->symbols(),
            ],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()
            ->with('status', 'success')
            ->with('message', 'Password update successfully!');
    }
}
