<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login(): void
    {
        $validated = $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($validated, $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        Session::regenerate();

        if (Auth::user()->role === 'admin') {
            $this->redirect(route('admin.dashboard'), navigate: true);
        } else {
            $this->redirect(session('url.intended', route('dashboard')), navigate: true);
        }
    }
}; ?>

<div class="min-h-screen flex items-center justify-center">
    <div class="auth-card w-full max-w-md">
        <a href="/" class="flex justify-center mb-6">
            <span class="text-4xl">🍲</span>
        </a>
        <h1 class="auth-title">Welcome Back!</h1>
        <p class="auth-subtitle">Please sign in to continue to your Seblak UMI account.</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit.prevent="login" class="space-y-6">
            <!-- Email Address -->
            <div>
                <label for="email" class="form-label">Email Address</label>
                <input wire:model.blur="email" id="email" class="form-input" type="email" required autofocus autocomplete="username" placeholder="you@example.com" />
                @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="form-label">Password</label>
                <input wire:model.blur="password" id="password" class="form-input" type="password" required autocomplete="current-password" placeholder="••••••••" />
                @error('password') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input wire:model.live="remember" id="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link text-sm" wire:navigate>
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="auth-button">
                    Sign In
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="auth-link" wire:navigate>
                Sign up here
            </a>
        </p>
    </div>
</div>

