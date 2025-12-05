<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class AdminProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $settings = Setting::all()->pluck('value', 'key');
        
        return view('admin.profile.edit', [
            'user' => $user,
            'site_name' => $settings['site_name'] ?? 'Seblak UMI AI',
            'site_logo' => $settings['site_logo'] ?? null,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp,bmp,svg', 'max:5120'],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()->route('admin.profile.edit')->with('success', 'Profil admin berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.edit')->with('success', 'Password berhasil diperbarui!');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp,bmp,svg', 'max:5120'],
        ]);

        // Update Site Name
        Setting::updateOrCreate(
            ['key' => 'site_name'],
            ['value' => $request->site_name]
        );

        // Update Site Logo
        if ($request->hasFile('site_logo')) {
            $oldLogo = Setting::where('key', 'site_logo')->value('value');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            
            Setting::updateOrCreate(
                ['key' => 'site_logo'],
                ['value' => $logoPath]
            );
        }

        return redirect()->route('admin.profile.edit')->with('success', 'Pengaturan website berhasil diperbarui!');
    }
}
