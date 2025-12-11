<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if (auth()->user()->role === 'admin') {
            $this->redirect(route('admin.dashboard', absolute: false), navigate: false);
            return;
        }

        $this->redirect(route('dashboard', absolute: false), navigate: false);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center px-4">
    <div class="auth-card w-full max-w-md">
        <a href="/" class="flex justify-center mb-6">
            <img src="{{ asset('images/logoseblak.jpeg') }}" alt="Seblak Umi AI Logo" class="h-16 w-16 object-contain rounded-lg">
        </a>
        <h1 class="auth-title">Welcome Back!</h1>
        <p class="auth-subtitle">Please sign in to continue to your Seblak UMI account.</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-6">
            <!-- Email Address -->
            <div>
                <label for="email" class="form-label">Email Address</label>
                <input wire:model="form.email" id="email" class="form-input" type="email" required autofocus autocomplete="username" placeholder="you@example.com" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="form-label">Password</label>
                <input wire:model="form.password" id="password" class="form-input" type="password" required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
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
