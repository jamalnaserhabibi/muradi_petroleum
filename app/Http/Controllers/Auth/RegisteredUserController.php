<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
// use Illuminate\Container\Attributes\Storage;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\table;

class RegisteredUserController extends Controller
{
    public function index() {}
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('admin.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // dd($request->all());

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:' . User::class],
            // 'usertype' => ['required', 'string', 'max:255'],
            'usertype' => ['required', 'in:admin,manager'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_photo' => ['required', 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
        ]);
        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }
        // dd($request->all());

        $user = User::create([
            'name' => $request->name,
            'usertype' => $request->usertype,
            'password' => Hash::make($request->password),
            'profile_photo' => $profilePhotoPath, // Save the photo path
        ]);

        event(new Registered($user));

        // Auth::login($user);

        return redirect(route('admin.useraccounts', absolute: false));
    }
    public function destroy(User $user)
    {
        // Delete the user's profile photo if it exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Delete the user from the database
        $user->delete();

        // Redirect back with a success message
        return redirect()->route('admin.useraccounts')->with('success', 'User deleted successfully.');
        // return redirect(route('admin.useraccounts', absolute: false));

    }
}
