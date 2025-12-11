<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: false);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center">
    <div class="auth-card w-full max-w-md">
        <a href="/" class="flex justify-center mb-6">
            <img src="{{ asset('images/logoseblak.jpeg') }}" alt="Seblak Umi AI Logo" class="h-16 w-16 object-contain rounded-lg">
        </a>
        <h1 class="auth-title">Create Your Account</h1>
        <p class="auth-subtitle">Join the Seblak UMI family and start ordering!</p>

        <form wire:submit="register" class="space-y-5">
            <!-- Name -->
            <div>
                <label for="name" class="form-label">Full Name</label>
                <input wire:model="name" id="name" class="form-input" type="text" name="name" required autofocus autocomplete="name" placeholder="Your Name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="form-label">Email Address</label>
                <input wire:model="email" id="email" class="form-input" type="email" name="email" required autocomplete="username" placeholder="you@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="form-label">Password</label>
                <input wire:model="password" id="password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input wire:model="password_confirmation" id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="auth-button">
                    Create Account
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="auth-link" wire:navigate>
                Sign in here
            </a>
        </p>
    </div>
</div>

